function validateStringSimple(val, min = 1, max = 32) {
    if (!/^[a-zA-Z0-9żźćńółęąśŻŹĆĄŚĘŁÓŃ -]+$/.test(val))
        return false;

    if (val.length < min || val.length > max)
        return false;

    return true;
}

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

function validateLogin(login) {
    if (!/^[a-zA-Z0-9]+$/.test(login))
        return false;
    if (login.length < 4 || login.length > 20)
        return false;
    return true;
}

function validateRecaptcha() {
    if (verifyToken == '' || isVerifying)
        return false;
    return true;
}

function validateCategoryName(name) {
    if (!/^[a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+$/.test(name))
        return false;
    if (name.length < 4 || name.length > 20)
        return false;

    return true;
}

function validateCategoryID(id) {
    if (!/^[0-9]+$/.test(id))
        return false;

    if (isNaN(parseInt(id)) || id < 0)
        return false;

    return true;
}

function validateIconFA(iconName) {
    if(!(/^[a-zA-Z0-9-]+$/.test(iconName)))
        return false;
    if(iconName.length <= 0)
        return true;
    return true;
}

function validateProductName(name) {
    if (!/^[a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ0-9-\(\)'"\/ ]+$/.test(name))
        return false;
    if (name.length < 4 || name.length > 120)
        return false;

    return true;
}
function validateProductPrice(price) {
    if (!/^[0-9.]+$/.test(price))
        return false;

    if (isNaN(parseFloat(price)) || price <= 0 || price > 999999)
        return false;

    return true;
}
function validateProductDescription(description) {
    if (description.length < 4)
        return false;

    return true;
}