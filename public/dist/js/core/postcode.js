function execPostcode(id_zipcode, id_addr1, id_addr2)
{
    var firstScript = document.getElementsByTagName('script')[0];
    var js = document.createElement('script');

    js.src = 'https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js';
    js.onload = function () {
        new daum.Postcode({
            oncomplete: function(data) {
                var addr = '';
                var extraAddr = '';

                addr = data.roadAddress; // 도로명
                // addr = data.jibunAddress; // 지번

                if(data.bname !== '' && /[동|로|가]$/g.test(data.bname))
                {
                    extraAddr += data.bname;
                }
                // 건물명이 있고, 공동주택일 경우 추가한다.
                if(data.buildingName !== '' && data.apartment === 'Y')
                {
                    extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                }
                // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                if(extraAddr !== '')
                {
                    extraAddr = ' (' + extraAddr + ')';
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById(id_zipcode).value = data.zonecode;
                document.getElementById(id_addr1).value = addr + ' ' + extraAddr;
                document.getElementById(id_addr2).focus();
            }
        }).open();
    };

    firstScript.parentNode.insertBefore(js, firstScript);
}
