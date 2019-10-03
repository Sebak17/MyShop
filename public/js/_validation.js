function validatePersonalNameLength(name) {
    if (name.length < 3 || name.length > 16)
        return false;
    return true;
}

function validatePersonalNameString(name) {
    if (!/^[a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ]+$/.test(name))
        return false;
    return true;
}

function validatePhone(phone) {
    if (phone.length != 9 || isNaN(parseInt(phone)))
        return false;
    return true;
}

function validateAdressLength(address) {
    if (address.length < 4 || address.length > 40)
        return false;
    return true;
}

function validateAdressString(address) {
    if (!/^[a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ0-9 /]+$/.test(address))
        return false;
    return true;
}

function validateZipCode(zipcode) {
    if (!/\b\d{2}-\d{3}\b/.test(zipcode))
        return false;
    return true;
}

function validateDistrict(district) {
    if (district > 16 || district < 1)
        return false;
    return true;
}

function validateCityLength(city) {
    if (city.length < 2 || city.length > 32)
        return false;
    return true;
}

function validateCityString(city) {
    if (!/^[a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+$/.test(city))
        return false;
    return true;
}

function validatePassword(password) {
    if (password.length < 4 || password.length > 30)
        return false;
    return true;
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function validateRecaptcha() {
    if(verifyToken == '' || isVerifying)
        return false;
    return true;
}