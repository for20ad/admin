$(document).ready(function() {


    $('#image-upload-button').on('click', function(e) {
        e.preventDefault();
        $fileInput.click(); // 숨겨진 파일 입력 요소 트리거
    });

    // 드래그한 파일이 드랍존으로 들어왔을 때
    $uploadContainer.on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $uploadContainer.addClass('dragover');
    });

    // 드래그한 파일이 드랍존을 떠났을 때
    $uploadContainer.on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $uploadContainer.removeClass('dragover');
    });

    // 파일이 드랍되었을 때
    $uploadContainer.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $uploadContainer.removeClass('dragover');

        const files = Array.from(e.originalEvent.dataTransfer.files);
        addFiles(files);
    });

    // 파일 선택 input에서 파일이 선택되었을 때
    $fileInput.on('change', function(e) {
        const files = Array.from(e.target.files);
        addFiles(files);
        $fileInput.val(''); // 파일 선택 후 input을 초기화하여 동일한 파일을 다시 선택할 수 있도록 함
    });

    // 파일을 추가하고 순서를 유지
    function addFiles(files) {
        files.forEach(file => {
            // 중복되지 않도록 하기 위해 name과 size로 검사
            if (!filesArray.some(f => f.name === file.name && f.size === file.size)) {
                filesArray.push(file);
            }
        });
        renderPreview(); // 미리보기 렌더링
        updateUI(); // UI 업데이트
    }

    // 미리보기 렌더링 함수
    function renderPreview() {
        $previewZone.empty(); // 기존 미리보기 초기화

        filesArray.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const $imgContainer = $('<div>').addClass('preview-container').css({
                    'position': 'relative',
                    'margin': '5px',
                    'width': '150px',
                    'height': '180px',
                    'cursor': 'move'
                }).attr('data-index', index);

                const $img = $('<img>').attr('src', e.target.result).css({
                    'width': '100%',
                    'height': '100%',
                    'object-fit': 'cover'
                });

                const $deleteButton = $('<button>').addClass('delete-button').html(
                `
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_286_1517)">
                <path d="M12.5 3.5L3.5 12.5" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12.5 12.5L3.5 3.5" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
                <defs>
                <clipPath id="clip0_286_1517">
                <rect width="16" height="16" fill="white"/>
                </clipPath>
                </defs>
                </svg>
                `
                ).css({
                    'position': 'absolute',
                    'top': '5px',
                    'right': '5px',
                    'background-color': '#616876',

                    'display': 'none'
                }).click(function() {
                    removeFile(index); // 파일 삭제 함수 호출 시 인덱스를 사용
                });

                const $orderLabel = $('<div>').addClass('order-label').css({
                    'text-align': 'center',
                    'margin-top': '5px',
                    'color': '#000'
                });

                $imgContainer.append($img).append($deleteButton).append($orderLabel);
                $previewZone.append($imgContainer);

                // 마우스오버 시 삭제 버튼 표시
                $imgContainer.hover(
                    function() {
                        $deleteButton.show();
                    },
                    function() {
                        $deleteButton.hide();
                    }
                );
            };
            reader.readAsDataURL(file);
        });

        if (filesArray.length > 0) {
            $uploadButton.show();
        } else {
            $uploadButton.hide();
        }

        // jQuery UI의 sortable 적용: 미리보기를 드래그하여 순서 변경 가능하게 함
        $previewZone.sortable({
            update: function() {
                const newFilesArray = [];
                $previewZone.children('.preview-container').each(function() {
                    const index = $(this).attr('data-index');
                    newFilesArray.push(filesArray[index]);
                });
                filesArray = newFilesArray; // 순서 변경 반영
                updateOrderLabels(); // 순서 라벨 업데이트
            }
        });
        $previewZone.disableSelection();

        updateOrderLabels(); // 미리보기 초기 렌더링 후 순서 라벨 업데이트
    }

    // UI 업데이트 함수
    function updateUI() {
        if (filesArray.length > 0) {
            $uploadText.hide();
            $uploadContainer.css('background-image', 'none');
        } else {
            $uploadText.show();
            $uploadContainer.css('background-image', 'url("/dist/img/file_upload_bg.svg")');
        }
    }

    // 순서 라벨 업데이트 함수
    function updateOrderLabels() {
        $previewZone.children('.preview-container').each(function(index) {

        });
    }

    // 파일 삭제 함수 (인덱스 기반)
    function removeFile(index) {
        filesArray.splice(index, 1); // 해당 인덱스의 파일 제거
        renderPreview(); // 미리보기 갱신
        updateUI(); // UI 업데이트
    }

    // 업로드 버튼 클릭 시 모든 파일을 서버로 업로드
    $uploadButton.on('click', function() {
        const formData = new FormData();

        for (let i = 0; i < filesArray.length; i++) {
            formData.append('files[]', filesArray[i]);
        }

        // 파일 업로드를 위한 AJAX 요청
        $.ajax({
            url: '/upload', // 여기에 서버의 파일 업로드 URL을 입력하세요
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $('#preloader').show();
            },
            success: function(response) {
                console.log('Files uploaded successfully:', response);

                $('#preloader').hide();
                // 업로드 후 미리보기 영역과 파일 배열 초기화
                filesArray = [];
                renderPreview();
                updateUI();
            },
            error: function(xhr, status, error) {
                console.error('Error uploading files:', error);
                $('#preloader').hide();
                alert('파일 업로드 중 오류가 발생했습니다.');
            }
        });
    });
});
