import org.apache.shiro.crypto.hash.Sha256Hash

class BootStrap {

    def init = { servletContext ->

       def user = new ShiroUser(username: "admin", passwordHash: new Sha256Hash("admin").toHex())
            user.addToPermissions("*:*")
            user.save()
    }
    def destroy = {
    }
}