TODO List
===
- [ ] DirectAuth - authenticate by typing password directly into chat
    - [ ] Basic functionalities
    - [ ] Prevent players sending their passwords in chat
        - [ ] Detect passwords as substrings rather than the whole message
- [ ] AuthRun - auto-run commands or do other things when player auths (include JoinRun too)
- [ ] XAuth - Extra database for ipconfig + arbitrary data saving
    - [ ] Database support
        - [ ] YAML
        - [ ] SQLite
        - [ ] MySQL
- [ ] AuthSite - A website hosted in the same environment that opens an HTTP server for auth-related stuff
- [ ] AuthCmds - extra auth-related commands
  - [ ] `/changepassword` - change password
  - [ ] `/ipconfig` - change IP-authentication configuration
