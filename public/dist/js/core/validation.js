function isValidateBizNum(value)
{
    var valueMap = value.replace(/-/gi, '').split('').map(function(item) {
        return parseInt(item, 10);
    });

    if (valueMap.length === 10)
    {
        var multiply = new Array(1, 3, 7, 1, 3, 7, 1, 3, 5);
        var checkSum = 0;

        for (var i = 0; i < multiply.length; ++i)
        {
            checkSum += multiply[i] * valueMap[i];
        }

        checkSum += parseInt((multiply[8] * valueMap[8]) / 10, 10);
        return Math.floor(valueMap[9]) === ((10 - (checkSum % 10)) % 10);
    }

    return false;
}

function isValidatePassword(str)
{
    return /^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&\(\)])[A-Za-z\d$@$!%*#?&\(\)]{8,}$/.test(str);
}

function isValidUserId(str)
{
    return /^[a-z]+[a-z0-9]{3,15}$/.test(str);
}

function isValidNickname(str)
{
    if (str.length >= 4 && str.length <= 16)
    {
        return /^[ㄱ-ㅎ|가-힣|a-z|A-Z|0-9|\*]+$/.test(str);
    }

    return false;
}
