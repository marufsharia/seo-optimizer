# Release Checklist

Use this checklist when preparing a new release.

## Pre-Release

### Code Quality
- [ ] All tests pass
- [ ] Code follows PSR-12 standards
- [ ] No debug code or console.log statements
- [ ] All TODOs are addressed or documented
- [ ] Code is properly commented
- [ ] No security vulnerabilities

### Documentation
- [ ] README.md is up to date
- [ ] USER_GUIDE.md reflects all features
- [ ] CHANGELOG.md is updated with all changes
- [ ] EXAMPLES.md includes new features
- [ ] API documentation is current
- [ ] Installation instructions are accurate

### Testing
- [ ] Manual testing completed
- [ ] All features work as expected
- [ ] Tested on fresh installation
- [ ] Tested upgrade from previous version
- [ ] Tested on different PHP versions (8.2, 8.3)
- [ ] Tested on different databases (MySQL, PostgreSQL, SQLite)
- [ ] Tested in production-like environment

### Version Management
- [ ] Version number updated in `composer.json`
- [ ] Version number updated in `plugin.json`
- [ ] Version number updated in `Plugin.php` `getVersion()`
- [ ] Version number updated in documentation
- [ ] CHANGELOG.md has new version section

## Release Process

### 1. Prepare Release Branch
```bash
git checkout -b release/v1.x.x
```

### 2. Update Version Numbers
- composer.json
- plugin.json
- src/Plugin.php
- Documentation files

### 3. Update CHANGELOG.md
```markdown
## [1.x.x] - YYYY-MM-DD

### Added
- New feature descriptions

### Changed
- Changed feature descriptions

### Fixed
- Bug fix descriptions

### Deprecated
- Deprecated feature descriptions

### Removed
- Removed feature descriptions

### Security
- Security fix descriptions
```

### 4. Commit Changes
```bash
git add .
git commit -m "chore: prepare release v1.x.x"
```

### 5. Create Pull Request
- Create PR from release branch to main
- Request review from maintainers
- Ensure all CI checks pass

### 6. Merge to Main
```bash
git checkout main
git merge release/v1.x.x
```

### 7. Create Git Tag
```bash
git tag -a v1.x.x -m "Release version 1.x.x"
git push origin v1.x.x
```

### 8. Create GitHub Release
- Go to GitHub Releases
- Click "Draft a new release"
- Select the tag
- Title: "SEO Optimizer v1.x.x"
- Description: Copy from CHANGELOG.md
- Attach any necessary files
- Publish release

### 9. Update Packagist
- Packagist should auto-update from GitHub
- Verify package appears on Packagist
- Check version is correct

## Post-Release

### Verification
- [ ] Release appears on GitHub
- [ ] Tag is created
- [ ] Packagist is updated
- [ ] Installation via Composer works
- [ ] Documentation links work
- [ ] Download links work

### Communication
- [ ] Announce on project website
- [ ] Post on social media
- [ ] Notify users via email (if applicable)
- [ ] Update project roadmap
- [ ] Close related GitHub issues

### Cleanup
- [ ] Delete release branch
- [ ] Archive old documentation versions
- [ ] Update project board
- [ ] Plan next release

## Hotfix Release

For urgent bug fixes:

### 1. Create Hotfix Branch
```bash
git checkout -b hotfix/v1.x.y main
```

### 2. Fix the Bug
- Make minimal changes
- Focus only on the critical issue
- Add tests if possible

### 3. Update Version
- Increment patch version (1.0.0 → 1.0.1)
- Update CHANGELOG.md

### 4. Test Thoroughly
- Test the fix
- Ensure no regressions
- Test on production-like environment

### 5. Merge and Release
```bash
git checkout main
git merge hotfix/v1.x.y
git tag -a v1.x.y -m "Hotfix version 1.x.y"
git push origin main
git push origin v1.x.y
```

### 6. Create GitHub Release
- Mark as hotfix in description
- Explain what was fixed
- Recommend immediate upgrade

## Version Numbering

We follow [Semantic Versioning](https://semver.org/):

- **MAJOR** (1.0.0): Breaking changes
- **MINOR** (1.1.0): New features, backwards compatible
- **PATCH** (1.0.1): Bug fixes, backwards compatible

### Examples

- `1.0.0` → `2.0.0`: Breaking API changes
- `1.0.0` → `1.1.0`: New redirect bulk import feature
- `1.0.0` → `1.0.1`: Fix sitemap caching bug

## Release Schedule

- **Major releases**: Annually or as needed
- **Minor releases**: Quarterly or as features are ready
- **Patch releases**: As needed for bug fixes
- **Hotfixes**: Immediately for critical issues

## Support Policy

- **Current version**: Full support
- **Previous minor version**: Security fixes only
- **Older versions**: No support (upgrade recommended)

## Rollback Plan

If a release has critical issues:

### 1. Assess the Issue
- Determine severity
- Check if hotfix is possible
- Decide on rollback vs. hotfix

### 2. Communicate
- Notify users immediately
- Explain the issue
- Provide workaround if available

### 3. Rollback (if necessary)
```bash
# Revert the release commit
git revert <commit-hash>
git push origin main

# Delete the problematic tag
git tag -d v1.x.x
git push origin :refs/tags/v1.x.x

# Update GitHub release
# Mark as "yanked" or delete
```

### 4. Fix and Re-release
- Fix the issue
- Increment version
- Release as new version

## Checklist Summary

Before pushing the release button:

✅ Code is tested and working
✅ Documentation is complete
✅ Version numbers are updated
✅ CHANGELOG is updated
✅ Git tag is created
✅ GitHub release is drafted
✅ Communication plan is ready

---

**Remember**: It's better to delay a release than to release broken code!
