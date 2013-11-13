import org.apache.shiro.authc.AccountException
import org.apache.shiro.authc.IncorrectCredentialsException
import org.apache.shiro.authc.UnknownAccountException
import org.apache.shiro.authc.SimpleAccount
import org.apache.shiro.authz.permission.WildcardPermission

class ShiroDbRealm {

    static authTokenClass = org.apache.shiro.authc.UsernamePasswordToken
    def credentialMatcher
    def shiroPermissionResolver

    def authenticate(authToken) {
        log.info "Attempting to authenticate ${authToken.username} in DB realm..."
        def username = authToken.username
        if (username == null) {
            throw new AccountException("Null usernames are not allowed by this realm.")
        }
        def user = ShiroUser.findByUsername(username)
        if (!user) {
            throw new UnknownAccountException("No account found for user [${username}]")
        }
        log.info "Found user '${user.username}' in DB"
        def account = new SimpleAccount(username, user.passwordHash, "ShiroDbRealm")
        if (!credentialMatcher.doCredentialsMatch(authToken, account)) {
            log.info "Invalid password (DB realm)"
            throw new IncorrectCredentialsException("Invalid password for user '${username}'")
        }
        return account
    }

    def hasRole(principal, roleName) {
        def roles = ShiroUser.withCriteria {
            roles {
                eq("name", roleName)
            }
            eq("username", principal)
        }
        return roles.size() > 0
    }

    def hasAllRoles(principal, roles) {
        def r = ShiroUser.withCriteria {
            roles {
                'in'("name", roles)
            }
            eq("username", principal)
        }
        return r.size() == roles.size()
    }

    def isPermitted(principal, requiredPermission) {
        def user = ShiroUser.findByUsername(principal)
        def permissions = user.permissions
        def retval = permissions?.find { permString ->
            def perm = shiroPermissionResolver.resolvePermission(permString)
            if (perm.implies(requiredPermission)) {
                return true
            }
            else {
                return false
            }
        }
        if (retval != null) {
            return true
        }
        def results = ShiroUser.findByUsername(principal).roles.permissions.flatten().unique()
        retval = results.find { permString ->
            def perm = shiroPermissionResolver.resolvePermission(permString.toString())
            if (perm.implies(requiredPermission)) {
                return true
            }
            else {
                return false
            }
        }
        if (retval != null) {
            return true
        }
        else {
            return false
        }
    }
}
