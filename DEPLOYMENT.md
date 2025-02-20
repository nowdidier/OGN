# Project Deployment Instructions

1. Create your project folder:
   - Example: `mkdir my-project` inside `/c:/Users/Nova/Downloads/Compressed/OGN`

2. Initialize Git:
   - `cd my-project`
   - `git init`

3. Create and commit your initial files:
   - `git add .`
   - `git commit -m "Initial commit"`

4. On GitHub, create your new repository.

5. Link your local repository to GitHub:
   - `git remote add origin <repository-url>`

6. Push your changes:
   - `git push -u origin master` (or `main` as appropriate)

7. Troubleshooting SSH errors:
   - If you encounter an error like:
       "git@github.com: Permission denied (publickey)."
       "fatal: Could not read from remote repository."
   - Verify that you have an SSH key generated (usually at ~/.ssh/id_rsa.pub).
   - Add your SSH public key to your GitHub account at https://github.com/settings/keys.
   - Ensure ssh-agent is running and add your SSH key using:
       `ssh-add ~/.ssh/id_rsa`
   - Alternatively, consider using HTTPS for remotes if SSH setup isn’t working.

8. Generate and add SSH key:
   - Generate a new SSH key:
     `ssh-keygen -t rsa -b 4096 -C "your_email@example.com"`
     (Press Enter to accept default file location, optionally add a passphrase.)
   - Start the ssh-agent in the background:
     `eval "$(ssh-agent -s)"`
   - Add your SSH private key to the ssh-agent:
     `ssh-add ~/.ssh/id_rsa`
   - Copy the SSH public key to your clipboard (for Windows):
     `clip < ~/.ssh/id_rsa.pub`
   - In GitHub, navigate to Settings → SSH and GPG keys, click "New SSH key" and paste your copied key.

9. Troubleshooting push errors:
   - Run: `git fsck --full` to check repository integrity.
   - If corruption is found, try: `git gc --prune=now` to clean up your repository.
   - Alternatively, consider resetting your HEAD:
     `git reset --hard HEAD`
   - If problems persist, re-clone your repository and reapply your changes.
