package manager


class Customer {


    String name
    String address
    String phone
    String email
    String apiKey
    String keyEnabled
    Date createOn
    static hasMany = [clicKs:Click]


    static mapping = {
    }
    
	static constraints = {
    }
	

}
