<?php
if (isset($pageDatas) === false) {
    $pageDatas = [];
}
$sQueryString = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

$aConfig = _elm($pageDatas, 'aConfig', []);

$aLists = _elm($pageDatas, 'aDatas', []);
$aKeyword= _elm($pageDatas, 'aKeyword', []);
$aCateTreeLists = _elm($pageDatas, 'cate_tree_lists', []);

$aGetData = _elm($pageDatas, 'getData', []);
?>
<!-- jQuery and jQuery UI CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
<style>
#keyword-layer-${groupCount} {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 4px;
}

</style>
<!-- 본문 -->
<div class="container-fluid" style="">
    <!-- 카드 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">카테고리 관리</h3>
    </div>

    <div class="row row-deck row-cards">
        <!-- 카테고리 카드 -->
        <div class="col-md-5 category-container-parent">
            <div class="col-md-4 category-container">
                <div class="card" style="height: inherit">
                    <!-- 카드 타이틀 -->
                    <div class="accordion-card"
                        style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4"
                                    fill="none">
                                    <circle cx="2" cy="2" r="2" fill="#206BC4" />
                                </svg>
                                <p class="body1-c ms-2 mt-1">
                                    카테고리
                                </p>
                                <span style="margin-left:1.3rem"> * 더블클릭으로 아코디언</span>
                            </div>
                            <!-- 추가 버튼 -->
                            <div>
                                <?php
                                echo getIconButton([
                                    'txt' => '1차 카테고리 생성',
                                    'icon' => 'add',
                                    'buttonClass' => 'btn-sm btn-white',
                                    'buttonStyle' => '',
                                    'width' => '21',
                                    'height' => '20',
                                    'stroke' => '#1D273B',
                                    'extra' => [
                                        'type' => 'button',
                                        'onclick' => 'openLayer(\'\',0);',
                                    ]
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-body" style="overflow-y: auto">
                        <!-- 카테고리 wrapper -->
                        <div id="category-list">
                            <?php
                                    if (empty($aCateTreeLists) === false) {
                                        foreach ($aCateTreeLists as $kIDX => $vCATE) {
                                            $cate_idx = _elm($vCATE, 'C_IDX', 0);
                                            $parent_idx = _elm($vCATE, 'C_PARENT_IDX', 0);
                                    ?>
                            <div class="category-wrapper" data-idx="<?php echo $cate_idx ?>">
                                <div class="category-div d-flex align-items-center justify-content-between detail"
                                    data-idx="<?php echo $cate_idx ?>" data-parent-idx="<?php echo $parent_idx ?>">
                                    <div class="d-flex align-items-center gap-2">
                                        <!-- 토글 버튼 -->
                                        <svg class="toggle-icon" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                            onclick="openLayer('','<?php echo $cate_idx?>')">
                                            <rect width="16" height="16" rx="4" fill="#616876"></rect>
                                            <path d="M4.5 8H11.5" stroke="white" stroke-width="1.66666"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M8 11.5L8 4.5" stroke="white" stroke-width="1.66666"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>


                                        <!-- 카테고리명 -->
                                        <p>[<?php echo _elm($vCATE, 'C_CATE_CODE') ?>]
                                            <?php echo _elm($vCATE, 'C_CATE_NAME') ?> (
                                            <?php echo count( _elm($vCATE, 'CHILD', []) ) ?> )</p>
                                    </div>
                                    <!-- 위,아래 아이콘 -->
                                    <div class="move-icons">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 32 32" fill="none">
                                            <g clip-path="url(#clip0_492_18580)">
                                                <path
                                                    d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z"
                                                    fill="#616876" />
                                                <path
                                                    d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z"
                                                    fill="#616876" />
                                                <path
                                                    d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z"
                                                    fill="#616876" />
                                                <path
                                                    d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z"
                                                    fill="#616876" />
                                                <path
                                                    d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z"
                                                    fill="#616876" />
                                                <path
                                                    d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z"
                                                    fill="#616876" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_492_18580">
                                                    <rect width="32" height="32" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </div>
                                </div>
                                <?php
                                        if (empty(_elm($vCATE, 'CHILD')) === false) {
                                            foreach (_elm($vCATE, 'CHILD', []) as $kIDX_CHILD => $vCATE_CHILD) {
                                                $cate_idx = _elm($vCATE_CHILD, 'C_IDX', 0);
                                                $parent_idx = _elm($vCATE_CHILD, 'C_PARENT_IDX', 0);
                                        ?>
                                <div class="child-container" data-idx="<?php echo $cate_idx ?>"
                                    data-parent-idx="<?php echo $parent_idx ?>" style="display: none;">
                                    <!-- 2차 카테고리 -->
                                    <div class="child-category-wrapper detail" data-idx="<?php echo $cate_idx ?>"
                                        data-parent-idx="<?php echo $parent_idx ?>">
                                        <div class="child-category d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- 토글 버튼 -->
                                                <svg class="toggle-icon" width="16" height="16" viewBox="0 0 16 16"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg"
                                                    onclick="openLayer('','<?php echo $cate_idx?>')">
                                                    <rect width="16" height="16" rx="4" fill="#616876"></rect>
                                                    <path d="M4.5 8H11.5" stroke="white" stroke-width="1.66666"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M8 11.5L8 4.5" stroke="white" stroke-width="1.66666"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                                <!-- 카테고리명 -->
                                                <p>[<?php echo _elm($vCATE_CHILD, 'C_CATE_CODE') ?>]
                                                    <?php echo _elm($vCATE_CHILD, 'C_CATE_NAME') ?> (
                                                    <?php echo count( _elm($vCATE_CHILD, 'CHILD', []) ) ?> ) </p>
                                            </div>
                                            <div class="move-icons">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 32 32" fill="none">
                                                    <g clip-path="url(#clip0_492_18580)">
                                                        <path
                                                            d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z"
                                                            fill="#616876" />
                                                        <path
                                                            d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z"
                                                            fill="#616876" />
                                                        <path
                                                            d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z"
                                                            fill="#616876" />
                                                        <path
                                                            d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z"
                                                            fill="#616876" />
                                                        <path
                                                            d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z"
                                                            fill="#616876" />
                                                        <path
                                                            d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z"
                                                            fill="#616876" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_492_18580">
                                                            <rect width="32" height="32" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </div>
                                        </div>
                                        <?php
                                                if (empty($vCATE_CHILD['CHILD']) === false) {
                                                    foreach (_elm($vCATE_CHILD, 'CHILD', []) as $kIDX_GRAND_CHILD => $vCATE_GRAND_CHILD) {
                                                        $cate_idx = _elm($vCATE_GRAND_CHILD, 'C_IDX', 0);
                                                        $parent_idx = _elm($vCATE_GRAND_CHILD, 'C_PARENT_IDX', 0);
                                                ?>
                                        <div class="child-container2" data-idx="<?php echo $cate_idx ?>"
                                            data-parent-idx="<?php echo $parent_idx ?>" style="display: none;">
                                            <!-- 3차 카테고리 -->
                                            <div class="child-category d-flex align-items-center justify-content-between detail"
                                                data-idx="<?php echo $cate_idx ?>"
                                                data-parent-idx="<?php echo $parent_idx ?>">
                                                <div class="d-flex align-items-center gap-2">
                                                    <!-- 토글 버튼 -->

                                                    <!-- 카테고리명 -->
                                                    <p>[<?php echo _elm($vCATE_GRAND_CHILD, 'C_CATE_CODE') ?>]
                                                        <?php echo _elm($vCATE_GRAND_CHILD, 'C_CATE_NAME') ?></p>
                                                </div>
                                                <div class="move-icons">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        viewBox="0 0 32 32" fill="none">
                                                        <g clip-path="url(#clip0_492_18580)">
                                                            <path
                                                                d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z"
                                                                fill="#616876" />
                                                            <path
                                                                d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z"
                                                                fill="#616876" />
                                                            <path
                                                                d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z"
                                                                fill="#616876" />
                                                            <path
                                                                d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z"
                                                                fill="#616876" />
                                                            <path
                                                                d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z"
                                                                fill="#616876" />
                                                            <path
                                                                d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z"
                                                                fill="#616876" />
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_492_18580">
                                                                <rect width="32" height="32" fill="white" />
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                                    }
                                                }
                                                ?>
                                    </div>
                                </div>
                                <?php
                                            }
                                        }
                                        ?>
                            </div>
                            <?php
                                        }
                                    }
                                    ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- 카테고리 정보 카드 -->
        <div id="viewData" class="col-md" style="height: fit-content; overflow: auto"></div>
    </div>
</div>
<!-- Modal E-->
<script>
let groupCount = 0; // 그룹 카운트
let columnCounts = {}; // 그룹별 행 개수를 관리하는 객체
let groups = []; // 지문 관리 배열
var keywords = <?php echo json_encode($aKeyword); ?>;
    function addGroup() {
        groupCount++;
        columnCounts[groupCount] = 1;  // 새 그룹 생성 시 첫 번째 행으로 초기화
        groups.push(groupCount);  // 지문 그룹 추가

        const newGroup = `
            <div class="form-wrap sortable-item" id="group-wrap-${groupCount}">
                <div style="flex: 1;">
                    <label class="group-label move">
                        <div class="move-icons ui-sortable-handle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 32 32" fill="none">
                                <g clip-path="url(#clip0_492_18580)">
                                    <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"></path>
                                    <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"></path>
                                    <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"></path>
                                    <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"></path>
                                    <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"></path>
                                    <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_492_18580">
                                        <rect width="32" height="32" fill="white"></rect>
                                    </clipPath>
                                </defs>
                            </svg>
                            지문 ${groupCount}

                        </div>
                    </label>
                </div>
                <div class="form-group" id="group-${groupCount}">
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <!-- 리뷰 키워드 -->

                        <div style="flex: 1;">

                            <label for="keyword-select-${groupCount}" style="display: inline-block; margin-right: 10px;">리뷰 키워드</label>

                            <select class="form-control" id="keyword-select-${groupCount}" name="questions[${groupCount}][keyword]" style="width: 150px; display: inline-block;">
                                <option value="">선택</option>
                            </select>

                        </div>
                        <!-- 리뷰 타이틀 -->
                        <div style="flex: 1.4; min-width: 200px;">
                            <label for="title-input-${groupCount}" style="display: inline-block; margin-right: 10px;">리뷰 타이틀</label>
                            <input
                                id="title-input-${groupCount}"
                                type="text"
                                class="form-control question-input"
                                name="questions[${groupCount}][question]"
                                placeholder="지문을 입력하세요"
                                style="width: 300px; display: inline-block;"
                            >
                        </div>
                    </div>
                    <div style="flex: 0 0 auto;">
                        <button type="button" class="btn-sm" style="background-color:#616876; margin-top:1.2rem; padding: 5px 10px; font-size: 12px; border:0;" onclick="deleteGroup(${groupCount})">삭제</button>
                    </div>
                </div>
                <!-- 값 설명 및 값 입력이 행으로 추가되는 부분 -->
                <div class="form-group value-group" id="value-container-${groupCount}" style="display: flex; align-items: flex-start;">
                    <div class="value-row" id="value-row-${groupCount}" style="flex:1">
                        ${createRowMarkup(groupCount, 1)}
                    </div>
                    <div class="button-wrapper" style="flex: 0 0 auto; margin-left: 10px; display: flex; flex-direction: column; align-items: flex-start;">
                        <button type="button" class="btn-sm btn-white" style="margin-top:1.2rem; font-size: 12px;" onclick="addRow(${groupCount})"> + 추가</button>
                        <button type="button" class="btn-sm btn-white" style="font-size: 12px;" onclick="deleteLastRow(${groupCount})"> - 삭제</button>
                    </div>
                </div>
            </div>
        `;
        $('#inputContainer').append(newGroup);

        // 키워드 목록 추가
        keywords.forEach(keyword => {
            const option = `<option value="${keyword.K_IDX}">${keyword.K_NAME}</option>`;
            $(`#keyword-select-${groupCount}`).append(option);

            const keywordDiv = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;" id="keyword-row-${keyword.K_IDX}">
                    <span>${keyword.K_NAME}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" onclick="deleteKeyword(event, '${keyword.K_IDX}')">
                    <path d="M2.5 10C2.5 10.9849 2.69399 11.9602 3.0709 12.8701C3.44781 13.7801 4.00026 14.6069 4.6967 15.3033C5.39314 15.9997 6.21993 16.5522 7.12987 16.9291C8.03982 17.306 9.01509 17.5 10 17.5C10.9849 17.5 11.9602 17.306 12.8701 16.9291C13.7801 16.5522 14.6069 15.9997 15.3033 15.3033C15.9997 14.6069 16.5522 13.7801 16.9291 12.8701C17.306 11.9602 17.5 10.9849 17.5 10C17.5 9.01509 17.306 8.03982 16.9291 7.12987C16.5522 6.21993 15.9997 5.39314 15.3033 4.6967C14.6069 4.00026 13.7801 3.44781 12.8701 3.0709C11.9602 2.69399 10.9849 2.5 10 2.5C9.01509 2.5 8.03982 2.69399 7.12987 3.0709C6.21993 3.44781 5.39314 4.00026 4.6967 4.6967C4.00026 5.39314 3.44781 6.21993 3.0709 7.12987C2.69399 8.03982 2.5 9.01509 2.5 10Z" fill="white"/>
                    <path d="M8.33333 8.33333L11.6667 11.6667L8.33333 8.33333ZM11.6667 8.33333L8.33333 11.6667L11.6667 8.33333Z" fill="white"/>
                    <path d="M8.33333 8.33333L11.6667 11.6667M11.6667 8.33333L8.33333 11.6667M2.5 10C2.5 10.9849 2.69399 11.9602 3.0709 12.8701C3.44781 13.7801 4.00026 14.6069 4.6967 15.3033C5.39314 15.9997 6.21993 16.5522 7.12987 16.9291C8.03982 17.306 9.01509 17.5 10 17.5C10.9849 17.5 11.9602 17.306 12.8701 16.9291C13.7801 16.5522 14.6069 15.9997 15.3033 15.3033C15.9997 14.6069 16.5522 13.7801 16.9291 12.8701C17.306 11.9602 17.5 10.9849 17.5 10C17.5 9.01509 17.306 8.03982 16.9291 7.12987C16.5522 6.21993 15.9997 5.39314 15.3033 4.6967C14.6069 4.00026 13.7801 3.44781 12.8701 3.0709C11.9602 2.69399 10.9849 2.5 10 2.5C9.01509 2.5 8.03982 2.69399 7.12987 3.0709C6.21993 3.44781 5.39314 4.00026 4.6967 4.6967C4.00026 5.39314 3.44781 6.21993 3.0709 7.12987C2.69399 8.03982 2.5 9.01509 2.5 10Z" stroke="#616876" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                </div>
            `;
            $(`#keyword-list-${groupCount}`).append(keywordDiv);
        });

    }
    // 키워드 레이어 토글
    function toggleLayer(groupCount) {
        const layer = document.getElementById(`keyword-layer`);
        layer.style.display = layer.style.display === 'none' ? 'block' : 'none';
    }



    // 행 추가 함수 (5개로 제한)
    function addRow(groupId) {
        const currentRowCount = $(`#value-row-${groupId} .row`).length;
        if (currentRowCount >= 5) {
            alert('최대 5개의 행만 추가할 수 있습니다.');
            return;
        }

        columnCounts[groupId]++;  // 그룹별 행 개수 증가
        const rowId = columnCounts[groupId];  // 현재 행 ID
        const newRow = createRowMarkup(groupId, rowId);

        $(`#value-row-${groupId}`).append(newRow);
        updateRowLabels(groupId);  // 행 번호 갱신
    }

   // 행 삭제 함수 (마지막 행만 삭제)
    function deleteLastRow(groupId) {
        const currentRowCount = $(`#value-row-${groupId} .row`).length;
        if (currentRowCount > 1) {
            $(`#value-row-${groupId} .row`).last().remove();  // 마지막 행 삭제
            columnCounts[groupId]--;  // 행 개수 감소
            updateRowLabels(groupId);  // 번호 다시 매기기
        } else {
            alert('최소 1개의 행이 있어야 합니다.');
        }
    }

    // 지문 삭제 함수 (지문과 값 묶음 전체 삭제)
    function deleteGroup(groupId) {
        const groupIndex = groups.indexOf(groupId);
        if (groupIndex > -1) {
            groups.splice(groupIndex, 1);  // 지문 그룹 삭제
            $(`#group-wrap-${groupId}`).remove();  // 그룹 삭제
            delete columnCounts[groupId];  // 그룹의 행 개수 정보 삭제
            updateGroupLabels();  // 지문 번호 갱신
        }
    }

    // 행 마크업 생성 함수
    function createRowMarkup(groupId, rowId) {
        return `
            <div class="row" id="row-${groupId}-${rowId}">
                <div style="flex: 1.6;">
                    <label>보기 ${rowId}</label>
                    <input type="text" class="form-control" name="questions[${groupId}][values][${rowId}][description]" placeholder="값 설명 입력">
                </div>
                <div style="flex: 0.4;">
                    <label>&nbsp;</label>
                    <select class="selectbox form-select" style="max-width:100px" name="questions[${groupId}][values][${rowId}][value]">
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5'>5</option>
                    </select>
                </div>
            </div>
        `;
    }

    // 행 번호 업데이트 함수
    function updateRowLabels(groupId) {
        const rows = $(`#value-row-${groupId} .row`);
        rows.each(function (index) {
            $(this).find('label:first').text(`보기 ${index + 1}`);  // 번호 갱신
        });
    }

    // 지문 번호 업데이트 함수
    function updateGroupLabels() {
        const groupLabels = $('.group-label');
        groupLabels.each(function (index) {
            $(this).text(`지문 ${index + 1}`);  // 지문 번호 갱신
        });
    }




  // sortable 설정

</script>

<?php
$owensView->setFooterJs('/assets/js/goods/category/lists.js');
$owensView->setFooterJs('/assets/js/goods/category/register.js');
$owensView->setFooterJs('/assets/js/goods/category/detail.js');

$script = "
";
$owensView->setFooterScript($script);