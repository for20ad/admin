<?php
if (isset($pageDatas) === false) {
    $pageDatas = [];
}
$sQueryString = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];

$aConfig = _elm($pageDatas, 'aConfig', []);

$aLists = _elm($pageDatas, 'aDatas', []);
$aBrandTreeLists = _elm($pageDatas, 'brand_tree_lists', []);

$aGetData = _elm($pageDatas, 'getData', []);
?>
<!-- jQuery and jQuery UI CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

<!-- 본문 -->
<div class="container-fluid">
    <!-- 카드 타이틀 -->
    <div class="card-title">
        <h3 class="h3-c">브랜드 관리</h3>
    </div>

    <div class="row row-deck row-cards">
        <!-- 카테고리 카드 -->
        <div class="col-md-4" style="height: 730px">
            <div class="card">
                <!-- 카드 타이틀 -->
                <div class="accordion-card" style="padding: 17px 24px; border: 0; border-bottom: 1px solid #e6e7e9;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4" fill="none">
                                <circle cx="2" cy="2" r="2" fill="#206BC4" />
                            </svg>
                            <p class="body1-c ms-2 mt-1">
                                브랜드
                            </p>
                            <span style="margin-left:1.3rem"> * 더블클릭으로 아코디언</span>
                        </div>
                        <!-- 추가 버튼 -->
                        <div>
                            <?php
                            echo getIconButton([
                                'txt' => '1차 브랜드 생성',
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
                        if (empty($aBrandTreeLists) === false) {
                            foreach ($aBrandTreeLists as $kIDX => $vBRAND) {
                                $brand_idx = _elm($vBRAND, 'C_IDX', 0);
                                $parent_idx = _elm($vBRAND, 'C_PARENT_IDX', 0);
                        ?>
                        <div class="category-wrapper" data-idx="<?php echo $brand_idx ?>">
                            <div class="category-div d-flex align-items-center justify-content-between detail" data-idx="<?php echo $brand_idx ?>" data-parent-idx="<?php echo $parent_idx ?>">
                                <div class="d-flex align-items-center gap-2">
                                    <!-- 토글 버튼 -->
                                    <svg class="toggle-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="openLayer('','<?php echo $brand_idx?>')">
                                        <rect width="16" height="16" rx="4" fill="#616876"></rect>
                                        <path d="M4.5 8H11.5" stroke="white" stroke-width="1.66666" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M8 11.5L8 4.5" stroke="white" stroke-width="1.66666" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>


                                    <!-- 카테고리명 -->
                                    <p>[<?php echo _elm($vBRAND, 'C_BRAND_CODE') ?>] <?php echo _elm($vBRAND, 'C_BRAND_NAME') ?> ( <?php echo count( _elm($vBRAND, 'CHILD', []) ) ?> )</p>
                                </div>
                                <!-- 위,아래 아이콘 -->
                                <div class="move-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 32 32" fill="none">
                                    <g clip-path="url(#clip0_492_18580)">
                                        <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"/>
                                        <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"/>
                                        <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"/>
                                        <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"/>
                                        <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"/>
                                        <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_492_18580">
                                        <rect width="32" height="32" fill="white"/>
                                        </clipPath>
                                    </defs>
                                </svg>
                                </div>
                            </div>
                            <?php
                            if (empty(_elm($vBRAND, 'CHILD')) === false) {
                                foreach (_elm($vBRAND, 'CHILD', []) as $kIDX_CHILD => $vBRAND_CHILD) {
                                    $brand_idx = _elm($vBRAND_CHILD, 'C_IDX', 0);
                                    $parent_idx = _elm($vBRAND_CHILD, 'C_PARENT_IDX', 0);
                            ?>
                            <div class="child-container" data-idx="<?php echo $brand_idx ?>" data-parent-idx="<?php echo $parent_idx ?>">
                                <!-- 2차 카테고리 -->
                                <div class="child-category-wrapper detail" data-idx="<?php echo $brand_idx ?>" data-parent-idx="<?php echo $brand_idx ?>">
                                    <div class="child-category d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <!-- 토글 버튼 -->
                                            <svg class="toggle-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" onclick="openLayer('','<?php echo $brand_idx?>')" >
                                                <rect width="16" height="16" rx="4" fill="#616876"></rect>
                                                <path d="M4.5 8H11.5" stroke="white" stroke-width="1.66666" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 11.5L8 4.5" stroke="white" stroke-width="1.66666" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            <!-- 카테고리명 -->
                                            <p>[<?php echo _elm($vBRAND_CHILD, 'C_BRAND_CODE') ?>] <?php echo _elm($vBRAND_CHILD, 'C_BRAND_NAME') ?> ( <?php echo count( _elm($vBRAND_CHILD, 'CHILD', []) ) ?> ) </p>
                                        </div>
                                        <div class="move-icons">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 32 32" fill="none">
                                            <g clip-path="url(#clip0_492_18580)">
                                                <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"/>
                                                <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"/>
                                                <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"/>
                                                <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"/>
                                                <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"/>
                                                <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"/>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_492_18580">
                                                <rect width="32" height="32" fill="white"/>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        </div>
                                    </div>
                                    <?php
                                    if (empty($vBRAND_CHILD['CHILD']) === false) {
                                        foreach (_elm($vBRAND_CHILD, 'CHILD', []) as $kIDX_GRAND_CHILD => $vBRAND_GRAND_CHILD) {
                                            $brand_idx = _elm($vBRAND_GRAND_CHILD, 'C_IDX', 0);
                                            $parent_idx = _elm($vBRAND_GRAND_CHILD, 'C_PARENT_IDX', 0);
                                    ?>
                                    <div class="child-container2" data-idx="<?php echo $brand_idx ?>" data-parent-idx="<?php echo $parent_idx ?>">
                                        <!-- 3차 카테고리 -->
                                        <div class="child-category d-flex align-items-center justify-content-between detail" data-idx="<?php echo $brand_idx ?>" data-parent-idx="<?php echo $parent_idx ?>">
                                            <div class="d-flex align-items-center gap-2">
                                                <!-- 토글 버튼 -->

                                                <!-- 카테고리명 -->
                                                <p>[<?php echo _elm($vBRAND_GRAND_CHILD, 'C_BRAND_CODE') ?>] <?php echo _elm($vBRAND_GRAND_CHILD, 'C_BRAND_NAME') ?></p>
                                            </div>
                                            <div class="move-icons">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 32 32" fill="none">
                                                <g clip-path="url(#clip0_492_18580)">
                                                    <path d="M11.5 9C12.3284 9 13 8.32843 13 7.5C13 6.67157 12.3284 6 11.5 6C10.6716 6 10 6.67157 10 7.5C10 8.32843 10.6716 9 11.5 9Z" fill="#616876"/>
                                                    <path d="M20.5 9C21.3284 9 22 8.32843 22 7.5C22 6.67157 21.3284 6 20.5 6C19.6716 6 19 6.67157 19 7.5C19 8.32843 19.6716 9 20.5 9Z" fill="#616876"/>
                                                    <path d="M11.5 17.5C12.3284 17.5 13 16.8284 13 16C13 15.1716 12.3284 14.5 11.5 14.5C10.6716 14.5 10 15.1716 10 16C10 16.8284 10.6716 17.5 11.5 17.5Z" fill="#616876"/>
                                                    <path d="M20.5 17.5C21.3284 17.5 22 16.8284 22 16C22 15.1716 21.3284 14.5 20.5 14.5C19.6716 14.5 19 15.1716 19 16C19 16.8284 19.6716 17.5 20.5 17.5Z" fill="#616876"/>
                                                    <path d="M11.5 26C12.3284 26 13 25.3284 13 24.5C13 23.6716 12.3284 23 11.5 23C10.6716 23 10 23.6716 10 24.5C10 25.3284 10.6716 26 11.5 26Z" fill="#616876"/>
                                                    <path d="M20.5 26C21.3284 26 22 25.3284 22 24.5C22 23.6716 21.3284 23 20.5 23C19.6716 23 19 23.6716 19 24.5C19 25.3284 19.6716 26 20.5 26Z" fill="#616876"/>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_492_18580">
                                                    <rect width="32" height="32" fill="white"/>
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

        <!-- 카테고리 정보 카드 -->
        <div id="viewData" class="col-md" style="height: fit-content"></div>
    </div>
</div>
<!-- Modal E-->
<script>

</script>

<?php
$owensView->setFooterJs('/assets/js/goods/brand/lists.js');
$owensView->setFooterJs('/assets/js/goods/brand/register.js');
$owensView->setFooterJs('/assets/js/goods/brand/detail.js');

$script = "
";
$owensView->setFooterScript($script);
