// GitHub Issues API Integration for Pengaduan Storage
// Menyimpan pengaduan sebagai GitHub Issues

class GitHubStorage {
    constructor() {
        // GitHub repository info
        this.owner = 'Haruu01';
        this.repo = 'pengaduan-warga';
        this.apiUrl = 'https://api.github.com';
        
        // GitHub Personal Access Token (perlu dibuat di GitHub Settings)
        // Token ini harus memiliki permission 'public_repo' atau 'repo'
        this.token = null; // Akan diset melalui UI
    }

    // Set GitHub token
    setToken(token) {
        this.token = token;
        localStorage.setItem('github_token', token);
    }

    // Get stored token
    getToken() {
        if (!this.token) {
            this.token = localStorage.getItem('github_token');
        }
        return this.token;
    }

    // Create new issue (pengaduan)
    async createComplaint(complaintData) {
        const token = this.getToken();
        if (!token) {
            throw new Error('GitHub token tidak tersedia. Silakan set token terlebih dahulu.');
        }

        const issueData = {
            title: `[PENGADUAN] ${complaintData.title}`,
            body: this.formatComplaintBody(complaintData),
            labels: [
                'pengaduan',
                `kategori:${complaintData.category_name}`,
                'status:pending'
            ]
        };

        try {
            const response = await fetch(`${this.apiUrl}/repos/${this.owner}/${this.repo}/issues`, {
                method: 'POST',
                headers: {
                    'Authorization': `token ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/vnd.github.v3+json'
                },
                body: JSON.stringify(issueData)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const issue = await response.json();
            return {
                id: issue.number,
                github_url: issue.html_url,
                api_url: issue.url,
                created_at: issue.created_at
            };
        } catch (error) {
            console.error('Error creating GitHub issue:', error);
            throw error;
        }
    }

    // Get all complaints (issues)
    async getAllComplaints() {
        try {
            const response = await fetch(
                `${this.apiUrl}/repos/${this.owner}/${this.repo}/issues?labels=pengaduan&state=all&per_page=100`,
                {
                    headers: {
                        'Accept': 'application/vnd.github.v3+json'
                    }
                }
            );

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const issues = await response.json();
            return issues.map(issue => this.parseComplaintFromIssue(issue));
        } catch (error) {
            console.error('Error fetching GitHub issues:', error);
            throw error;
        }
    }

    // Update complaint status
    async updateComplaintStatus(issueNumber, status, adminResponse = null) {
        const token = this.getToken();
        if (!token) {
            throw new Error('GitHub token tidak tersedia.');
        }

        try {
            // Update labels
            const newLabels = [`status:${status}`, 'pengaduan'];
            
            await fetch(`${this.apiUrl}/repos/${this.owner}/${this.repo}/issues/${issueNumber}`, {
                method: 'PATCH',
                headers: {
                    'Authorization': `token ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/vnd.github.v3+json'
                },
                body: JSON.stringify({
                    labels: newLabels
                })
            });

            // Add comment if admin response provided
            if (adminResponse) {
                await fetch(`${this.apiUrl}/repos/${this.owner}/${this.repo}/issues/${issueNumber}/comments`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `token ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/vnd.github.v3+json'
                    },
                    body: JSON.stringify({
                        body: `**Tanggapan Admin:**\n\n${adminResponse}\n\n---\n*Status diubah menjadi: ${status}*`
                    })
                });
            }

            return true;
        } catch (error) {
            console.error('Error updating complaint status:', error);
            throw error;
        }
    }

    // Format complaint data for GitHub issue body
    formatComplaintBody(complaint) {
        return `
## Detail Pengaduan

**Pelapor:** ${complaint.user_name}
**Email:** ${complaint.user_email}
**Kategori:** ${complaint.category_name}
**Lokasi:** ${complaint.location}

## Deskripsi
${complaint.description}

## Informasi Teknis
- **Tanggal:** ${new Date().toLocaleString('id-ID')}
- **User ID:** ${complaint.user_id}
- **Kategori ID:** ${complaint.category_id}

---
*Pengaduan ini dibuat melalui Sistem Pengaduan Warga*
        `.trim();
    }

    // Parse GitHub issue back to complaint format
    parseComplaintFromIssue(issue) {
        const statusLabel = issue.labels.find(label => label.name.startsWith('status:'));
        const categoryLabel = issue.labels.find(label => label.name.startsWith('kategori:'));
        
        return {
            id: issue.number,
            title: issue.title.replace('[PENGADUAN] ', ''),
            description: this.extractDescriptionFromBody(issue.body),
            status: statusLabel ? statusLabel.name.replace('status:', '') : 'pending',
            category_name: categoryLabel ? categoryLabel.name.replace('kategori:', '') : 'Unknown',
            user_name: this.extractUserFromBody(issue.body),
            user_email: this.extractEmailFromBody(issue.body),
            location: this.extractLocationFromBody(issue.body),
            created_at: issue.created_at,
            updated_at: issue.updated_at,
            github_url: issue.html_url,
            comments_count: issue.comments
        };
    }

    // Helper methods to extract data from issue body
    extractDescriptionFromBody(body) {
        const match = body.match(/## Deskripsi\n(.*?)\n\n## Informasi Teknis/s);
        return match ? match[1].trim() : '';
    }

    extractUserFromBody(body) {
        const match = body.match(/\*\*Pelapor:\*\* (.*)/);
        return match ? match[1] : 'Unknown';
    }

    extractEmailFromBody(body) {
        const match = body.match(/\*\*Email:\*\* (.*)/);
        return match ? match[1] : 'Unknown';
    }

    extractLocationFromBody(body) {
        const match = body.match(/\*\*Lokasi:\*\* (.*)/);
        return match ? match[1] : 'Unknown';
    }

    // Check if GitHub integration is available
    isAvailable() {
        return !!this.getToken();
    }

    // Get setup instructions
    getSetupInstructions() {
        return `
## Setup GitHub Integration

1. Buka GitHub Settings: https://github.com/settings/tokens
2. Klik "Generate new token (classic)"
3. Berikan nama token: "Pengaduan Warga App"
4. Pilih scope: "public_repo" atau "repo"
5. Klik "Generate token"
6. Copy token dan paste di aplikasi

**Catatan:** Token ini akan disimpan di localStorage browser Anda.
        `.trim();
    }
}

// Export for use in main app
window.GitHubStorage = GitHubStorage;
