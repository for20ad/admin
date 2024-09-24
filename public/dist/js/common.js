// Function to restore readonly attribute
function restoreReadonlyAttributes(mutationsList) {
    for (let mutation of mutationsList) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'readonly') {
            const target = mutation.target;
            if (!target.hasAttribute('readonly')) {
                target.setAttribute('readonly', '');
            }
        }
    }
}

// Observe changes in all input elements
function observeInputs() {
    const inputs = document.querySelectorAll('input[readonly]');
    inputs.forEach(input => {
        const observer = new MutationObserver(restoreReadonlyAttributes);
        observer.observe(input, {
            attributes: true // Listen for attribute changes
        });
    });
}

// Initial observer setup
observeInputs();

// Re-apply observer when new input elements are added dynamically
const bodyObserver = new MutationObserver(() => {
    observeInputs();
});
bodyObserver.observe(document.body, {
    childList: true,
    subtree: true
});
// // //datepicker 호출 yyyy mm dd
document.addEventListener("DOMContentLoaded", function () {
    var datepickerIcons = document.getElementsByClassName("datepicker");
    for (var i = 0; i < datepickerIcons.length; i++) {
        new Litepicker({
            lang: "ko-KR",
            format: "YYYY-MM-DD",
            element: datepickerIcons[i],
                buttonText: {
                    previousMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>`,
                    nextMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`,
                },
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    initializeDatepickers();
});

function initializeDatepickers() {
    var datepickerIcons = document.getElementsByClassName("datepicker");
    for (var i = 0; i < datepickerIcons.length; i++) {
        new Litepicker({
            lang: "ko-KR",
            format: "YYYY-MM-DD",
            element: datepickerIcons[i],
                buttonText: {
                    previousMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>`,
                    nextMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`,
                },
        });
    }
}


// 사업자 등록번호 포맷팅 함수
function formatBusinessNumber(value) {
    // 숫자만 남기기
    const numbersOnly = value.replace(/\D/g, '').slice(0, 10);

    let formattedNumber = numbersOnly;

    if (numbersOnly.length > 3 && numbersOnly.length <= 5) {
        formattedNumber = numbersOnly.slice(0, 3) + '-' + numbersOnly.slice(3);
    } else if (numbersOnly.length > 5) {
        formattedNumber = numbersOnly.slice(0, 3) + '-' + numbersOnly.slice(3, 5) + '-' + numbersOnly.slice(5);
    }

    return formattedNumber;
}

// 사업자 등록번호 입력 이벤트 리스너

$('input[data-business-number]').each(function() {
    const $inputField = $(this);
    $inputField.on('input', function() {
        const inputVal = $inputField.val();

        // 포맷팅된 번호로 설정
        const formattedNumber = formatBusinessNumber(inputVal);
        $inputField.val(formattedNumber);
    });

});
$(document).on('input', 'input[data-business-number]', function() {
    const $inputField = $(this);
    const inputVal = $inputField.val();

    // 포맷팅된 번호로 설정
    const formattedNumber = formatBusinessNumber(inputVal);

    $inputField.val(formattedNumber);
});


function showPasswordGroup(){
    $toggleDiv = $("#passGroup");

    if ($toggleDiv.is(':visible')) {
        $toggleDiv.slideUp();
        $("#passChangeBtn").text( '변경하기' );
        passwdChk = true;

    } else {
        $toggleDiv.slideDown();
        $("#passChangeBtn").text( '취소' );
        passwdChk = false;
    }
}
$(document).on('keydown', 'input[type="text"]', function(event) {

    if (event.keyCode === 13) {
        Enter_Remove();
    }
});
function Enter_Remove(){ // input 에서 enter 입력시 다음에 있는 button이 호출되는 현상때문에      // 엔터키의 코드는 13입니다.
	if(event.keyCode == 13){
		return false;
	}
}
flatpickr('.datetimepicker', {
    enableTime: true,
    dateFormat: 'Y-m-d H:i',
    time_24hr: true
});
flatpickr('.datepicker-icon', {
    enableTime: true,
    dateFormat: 'Y-m-d',
    time_24hr: true
});


function formatMobileNumber(numbersOnly) {
    let formattedNumber = numbersOnly;

    if (numbersOnly.length > 3 && numbersOnly.length <= 7) {
        formattedNumber = numbersOnly.replace(/(\d{3})(\d{1,4})/, '$1-$2');
    } else if (numbersOnly.length > 7) {
        formattedNumber = numbersOnly.replace(/(\d{3})(\d{4})(\d{1,4})/, '$1-$2-$3');
    }

    return formattedNumber;
}

$('input[data-max-length]').each(function() {
    const $inputField = $(this);
    const $wordCount = $inputField.siblings('.wordCount');
    const maxLength = $inputField.data('max-length');

    $inputField.on('input', function() {
        const currentLength = $inputField.val().length;
        $wordCount.text(`${currentLength}/${maxLength}`);
    });

    // 초기 글자 수 설정
    const initialLength = $inputField.val().length;
    $wordCount.text(`${initialLength}/${maxLength}`);
});
$(document).on('input', 'input[data-max-length]', function() {
    const $inputField = $(this);
    const $wordCount = $inputField.siblings('.wordCount');
    const maxLength = $inputField.data('max-length');

    const currentLength = $inputField.val().length;
    $wordCount.text(`${currentLength}/${maxLength}`);
});
$(document).on('input', 'input[data-mobile]', function() {
    const $inputField = $(this);
    const inputVal = $inputField.val();

    // 숫자만 남기기
    const numbersOnly = inputVal.replace(/\D/g, '').slice(0, 11);

    // 하이픈 넣기
    const formattedNumber = formatMobileNumber(numbersOnly);

    $inputField.val(formattedNumber);
});
// 기존 값이 있는 경우 초기 형식 설정
$('input[data-mobile]').each(function() {
    const $inputField = $(this);
    const inputVal = $inputField.val();

    const numbersOnly = inputVal.replace(/\D/g, '').slice(0, 11);
    const formattedNumber = formatMobileNumber(numbersOnly);

    $inputField.val(formattedNumber);
});


$('input[data-password]').on('input', function() {
    const $input = $(this);
    const password = $input.val();

    // 정규표현식을 사용하여 비밀번호 유효성 검사
    const isValid = /^(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,20}$/.test(password);

    // 비밀번호 유효성 검사에 따라 클래스 추가/제거
    if (isValid) {
        $input.removeClass('error');
    } else {
        $input.addClass('error');
    }
});

 // 이메일 형식 검사
 function validateEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email);
}


$('input[data-email]').on('input', function() {
    const $inputField = $(this);
    const emailVal = $inputField.val();
    const $errorSpan = $inputField.siblings('.email-error');

    if (!validateEmail(emailVal)) {
        $inputField.addClass('error');
    } else {
        $inputField.removeClass('error');
    }
});

// 기존 값이 있는 경우 초기 이메일 형식 검사
$('input[data-email]').each(function() {
    const $inputField = $(this);
    const emailVal = $inputField.val();
    const $errorSpan = $inputField.siblings('.email-error');

    if (!validateEmail(emailVal)) {
        $errorSpan.show();
    } else {
        $errorSpan.hide();
    }
});





//리스트 검색 레이어 (모바일)
const asideSearch = document.getElementById("asideSearch");
const asideOpen = document.getElementById("asideOpen");

if (asideSearch && asideOpen) {
  document.addEventListener("click", function (event) {
    if (event.target.closest(".card, #asideOpen")) {
      return;
    }
    asideSearch.classList.remove("open");
  });

  asideOpen.addEventListener("click", function () {
    asideSearch.classList.add("open");
  });
}

//input 최대값 계산
document.addEventListener("input", function (event) {
  if (event.target.matches(".max-text")) {
    let tsVal = event.target.value;
    let numChar = tsVal.length;
    const maxNum = event.target.getAttribute("maxlength");
    let lenDisplay = event.target
      .closest(".input-group")
      .querySelector(".max-len b");
    if (numChar > maxNum) {
      event.target.value = tsVal.substr(0, maxNum);
      lenDisplay.textContent = numChar;
    } else {
      lenDisplay.textContent = numChar;
    }
  }
});



//input 최대값 계산 - 페이지 로드 시 최대값 계산해서 출력
const maxLenSpans = document.querySelectorAll(".max-len");
maxLenSpans.forEach(function (maxLenSpan) {
  let numChar = maxLenSpan.closest(".input-group").querySelector(".max-text")
    .value.length;
  maxLenSpan.querySelector("b").textContent = numChar;
});

//input tel 숫자만 입력
function allowOnlyNumbersForTelInputs() {
  const telInputs = document.querySelectorAll('input[type="tel"]');
  telInputs.forEach(function (telInput) {
    telInput.addEventListener("input", function () {
      this.value = this.value.replace(/[^0-9]/g, "");
    });
  });
}
allowOnlyNumbersForTelInputs();

//체크박스 전체 체크
document.querySelectorAll(".label-control").forEach(function (labelControl) {
  labelControl.addEventListener("change", function (event) {
    const target = event.target;
    if (
      target.matches('input[type="checkbox"]') &&
      target.classList.contains("check-all")
    ) {
      const isChecked = target.checked;
      const checkboxes = labelControl.querySelectorAll(
        'input[type="checkbox"]'
      );
      checkboxes.forEach(function (checkbox) {
        checkbox.checked = isChecked;
      });
      if (!isChecked) {
        target.checked = false; // check-all 비활성화
      }
    } else if (
      target.matches('input[type="checkbox"]:not(.check-all)') &&
      !target.checked
    ) {
      const checkAllCheckbox = labelControl.querySelector(".check-all");
      if (checkAllCheckbox) {
        checkAllCheckbox.checked = false; // check-all 비활성화
      }
    }
  });
});

document.addEventListener('DOMContentLoaded', function () {
    // 체크박스 전체 체크

    if( document.querySelector('#checkAll') != null ){
        document.querySelector('#checkAll').addEventListener('change', function () {
            const isChecked = this.checked;
            document.querySelectorAll('.check-item').forEach(function (checkbox) {
                checkbox.checked = isChecked;
            });
        });

        // 개별 체크박스 체크/체크 해제 시 전체 체크박스 상태 업데이트
        document.querySelectorAll('.check-item').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const allChecked = document.querySelectorAll('.check-item:checked').length === document.querySelectorAll('.check-item').length;
                document.querySelector('#checkAll').checked = allChecked;
            });
        });
    }

});



/* 년도 셀렉트박스
data-start-year가 빈값이면 현재 년도부터 data-end-year까지 출력
data-end-year가 빈값이면 현재 data-start-year부터 현재까지 출력
둘다 값이 있으면 data-start-year 부터 data-end-year 까지 출력
<select class="form-select year-select" data-start-year="2010" data-end-year="" data-select-year="2020"></select>
*/
const currentYear = new Date().getFullYear();
const yearSelects = document.querySelectorAll(".year-select");

yearSelects.forEach((yearSelect) => {
  const startYear = yearSelect.dataset.startYear
    ? parseInt(yearSelect.dataset.startYear)
    : currentYear;
  const endYear = yearSelect.dataset.endYear
    ? parseInt(yearSelect.dataset.endYear)
    : currentYear;

  // 선택된 연도가 있는 경우
  const selectedYear = yearSelect.dataset.selectYear
    ? parseInt(yearSelect.dataset.selectYear)
    : null;

  for (let year = startYear; year <= endYear; year++) {
    const option = document.createElement("option");
    option.value = year;
    option.text = year;

    // 선택된 연도일 경우, 해당 옵션을 선택 상태로 만듦
    if (selectedYear && year === selectedYear) {
      option.selected = true;
    }

    yearSelect.appendChild(option);
  }
});

/* 월  셀렉트박스
data-start-month data-end-month 둘다 빈값이면 1~12까지 출력
data-start-month data-end-month의 값이 있으면 해당 값만큼 출력
data-start-month만 값이있으면 data-start-month값 부터 12까지 출력
data-end-month값만 있으면 1 ~ data-start-month까지 출력
<select class="form-select month-select" data-start-month="" data-end-month="" data-select-month="5"></select>
*/
const currentMonth = new Date().getMonth() + 1;
const monthSelects = document.querySelectorAll(".month-select");

monthSelects.forEach((monthSelect) => {
  const startMonth = monthSelect.dataset.startMonth
    ? parseInt(monthSelect.dataset.startMonth)
    : 1;
  const endMonth = monthSelect.dataset.endMonth
    ? parseInt(monthSelect.dataset.endMonth)
    : 12;
  const selectMonth = monthSelect.dataset.selectMonth
    ? parseInt(monthSelect.dataset.selectMonth)
    : currentMonth;

  for (let month = startMonth; month <= endMonth; month++) {
    const option = document.createElement("option");
    option.value = month;
    option.text = month;
    if (month === selectMonth) {
      option.selected = true;
    }
    monthSelect.appendChild(option);
  }
});

//시작날짜 종료날짜 버튼으로 날짜 변경
const dateControlBoxes = document.querySelectorAll(".date-control-box");
dateControlBoxes.forEach(function (dateControlBox) {
  const startDateInput = dateControlBox.querySelector(".startDate");
  const endDateInput = dateControlBox.querySelector(".endDate");
  const radioInputs = dateControlBox.querySelectorAll(".date-control");

  radioInputs.forEach(function (radioInput) {
    radioInput.addEventListener("change", function () {
      const value = this.value;

      let startDate, endDate;
      if (value === "today") {
        startDate = new Date();
        endDate = new Date();
      } else if (value === "week") {
        endDate = new Date();
        startDate = new Date(endDate.getTime() - 7 * 24 * 60 * 60 * 1000);
      } else if (!isNaN(Number(value))) {
        const months = Number(value);
        endDate = new Date();
        startDate = new Date(
          endDate.getFullYear(),
          endDate.getMonth() - months,
          endDate.getDate()
        );
      }
      startDateInput.value = formatDate(startDate);
      endDateInput.value = formatDate(endDate);
    });
  });
});

function formatDate(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
}

// 파일 추가 이벤트 등록
function fileSizeInMB(fileSize) {
  return fileSize / (1024 * 1024);
}

document.addEventListener("DOMContentLoaded", () => {
  const inputGroups = document.querySelectorAll(".input-group");

  inputGroups.forEach((inputGroup) => {
    const inputFile = inputGroup.querySelector(".file-add");
    const deleteButton = inputGroup.querySelector("button");

    if (inputFile && deleteButton) {
      deleteButton.addEventListener("click", () => {
        inputFile.value = "";
      });

      inputFile.addEventListener("change", () => {
        const files = inputFile.files;
        const allowedTypes = inputFile.getAttribute("file-type").split(" ");
        const maxSize = parseInt(inputFile.getAttribute("file-max-size"), 10);
        for (let i = 0; i < files.length; i++) {
          const file = files[i];
          if (
            !allowedTypes.includes(file.type.split("/")[1]) ||
            fileSizeInMB(file.size) > maxSize
          ) {
            alert(
              "업로드 불가능한 파일입니다. 파일 확장자 및 용량을 확인하세요."
            );
            inputFile.value = "";
            break;
          }
        }
      });
    }
  });
});

// 페이지 기록을 page-history에 표시함
// 쿠키에 페이지 기록
function addPageToCookie(url, title) {
  const existingPages = getPagesFromCookie();
  if (existingPages.find((page) => page.title === title)) {
    return;
  }

  existingPages.push({ url, title });
  document.cookie = `page_history=${JSON.stringify(existingPages)};path=/]}`;
}

// 쿠키에서 페이지 삭제
function removePageFromCookie(index) {
  const existingPages = getPagesFromCookie();
  existingPages.splice(index, 1);
  document.cookie = `page_history=${JSON.stringify(existingPages)};path=/`;
}

// 쿠키에서 페이지 가져오기
function getPagesFromCookie() {
  const cookie = document.cookie
    .split("; ")
    .find((row) => row.startsWith("page_history"));

  if (cookie) {
    var aCookie = cookie.split("=");
    console.log(aCookie);
    return JSON.parse(aCookie.replace("page_history=", ""));
  } else {
    return [];
  }
}

// 페이지 기록을 화면에 표시
function displayPageHistory() {
  const pageHistory = document.querySelector(".page-history");
  const pages = getPagesFromCookie();
  const pageTitleElement = document.querySelector(".page-title");
  const currentTitle = pageTitleElement
    ? pageTitleElement.textContent
    : document.title;

  pages.forEach((page, index) => {
    const item = document.createElement("div");
    item.className = "item";

    if (page.title === currentTitle) {
      item.classList.add("active");
    }

    const link = document.createElement("a");
    link.href = page.url;
    link.className = "name";
    link.textContent = page.title;
    item.appendChild(link);

    const deleteButton = document.createElement("button");
    deleteButton.type = "button";
    deleteButton.textContent = "삭제";
    deleteButton.addEventListener("click", () => {
      removePageFromCookie(index);
      item.remove();
    });
    item.appendChild(deleteButton);

    pageHistory.appendChild(item);
  });
}

function toggleForm( obj ){

    var $cardBody = obj.closest('.col-12').find('.card-body');
    if ($cardBody.is(':visible')) {
        $cardBody.slideUp();
        obj.find('svg > path').attr('d', 'M1 1L7 7L13 1'); // 반대 방향 아이콘
    } else {
        $cardBody.slideDown();
        obj.find('svg > path').attr('d', 'M1 7L7 1L13 7'); // 원래 방향 아이콘
    }
}
var Pagination = (function() {
    function pagingNumFunc(callbackMethod) {
        $(document).on('click', '.page-item a:not(#subList .page-item a)', function(e) {
            e.preventDefault();
            callbackMethod($(this).data('page'));
        });
    }

    function pagingSelectFunc(callbackMethod) {
        $(document).on('change', '.pagination-goto select:not(#subList .pagination-goto select)', function(e) {
            e.preventDefault();
            callbackMethod($(this).val());
        });
    }

    return {
        initPagingNumFunc: function(callbackMethod) {
            pagingNumFunc(callbackMethod);
        },
        initPagingSelectFunc: function(callbackMethod) {
            pagingSelectFunc(callbackMethod);
        }
    };
})();
var subPagination = (function() {
    function pagingNumFunc(callbackMethod) {
        $(document).on('click', '#subList .page-item a', function(e) {
            e.preventDefault();
            callbackMethod($(this).data('page'));
        });
    }

    function pagingSelectFunc(callbackMethod) {
        $(document).on('change', '#subList .pagination-goto select', function(e) {
            e.preventDefault();
            callbackMethod($(this).val());
        });
    }

    return {
        initPagingNumFunc: function(callbackMethod) {
            pagingNumFunc(callbackMethod);
        },
        initPagingSelectFunc: function(callbackMethod) {
            pagingSelectFunc(callbackMethod);
        }
    };
})();

// document.addEventListener("DOMContentLoaded", () => {
//   const pageTitleElement = document.querySelector(".page-title");
//   if (pageTitleElement) {
//     const pageTitle = pageTitleElement.innerHTML;
//     addPageToCookie(window.location.href, pageTitle);
//   }
//   displayPageHistory();
// });
function showTooltip(element, tooltipId) {
    const tooltipData = tooltipContent[tooltipId];
    if (!tooltipData) return;

    let existingTooltip = document.getElementById(tooltipId);
    if (existingTooltip) {
        existingTooltip.remove();
    }

    let tooltip = document.createElement('div');
    tooltip.className = 'tooltip_over';
    tooltip.id = tooltipId;

    let tooltipTitle = document.createElement('div');
    tooltipTitle.className = 'tooltip_title';
    tooltipTitle.innerText = tooltipData.title;

    let tooltipBody = document.createElement('div');
    tooltipBody.className = 'tooltip_body';
    tooltipBody.innerHTML = tooltipData.content; // HTML 태그를 포함한 내용 삽입

    tooltip.appendChild(tooltipTitle);
    tooltip.appendChild(tooltipBody);

    document.body.appendChild(tooltip);

    const tooltipWidth = tooltip.offsetWidth;
    const elementWidth = element.offsetWidth;
    const elementLeft = element.getBoundingClientRect().left + window.pageXOffset;
    const tooltipLeft = elementLeft - (tooltipWidth / 2) + (elementWidth / 2);

    tooltip.style.left = `${tooltipLeft - tooltipData.left}px`;
    tooltip.style.top = `${element.getBoundingClientRect().top + window.pageYOffset - tooltip.offsetHeight - tooltipData.height}px`;

    tooltip.style.display = 'block';

    setTimeout(function() {
        document.addEventListener('click', function(event) {
            if (!tooltip.contains(event.target) && event.target !== element) {
                hideTooltip(tooltipId);
            }
        }, { once: true });
    }, 0);
}

function hideTooltip(tooltipId) {
    let tooltip = document.getElementById(tooltipId);
    if (tooltip) {
        tooltip.remove();
    }
}