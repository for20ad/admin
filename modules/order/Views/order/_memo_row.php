<?php
helper(['form', 'owens_form']);
$view_datas = $owensView->getViewDatas();

// $groupedByDate 데이터가 있다고 가정합니다.
$aLists      = _elm($view_datas, 'lists', []);
$aOdrStatus  = _elm($view_datas, 'aOdrStatus', []);
$mOrdid      = _elm($view_datas, 'M_ORDID');
// 그룹화된 데이터가 비어 있지 않을 경우 출력
if (!empty($aLists)) {
    foreach ($aLists as $aKey => $aMemo) :
?>
    <div class="payInfo memoBox" data-memo-idx="<?php echo _elm( $aMemo, 'M_IDX' )?>">
        <!-- Content -->
        <div class="memo-box-content">

            <div class="flex-box">
                <span class="flex1">
                    <?php echo _elm($aMemo, 'M_WRITER_NAME'); ?>
                </span>
                <div class="toggle-area">
                    <span class="flex-right">
                        <span class="box-radius <?php echo _elm($aMemo, 'M_STATUS') !== 'READY' ? 'active' : ''; ?>">
                        <?php echo _elm($aMemo, 'M_STATUS') == 'READY' ? '미해결' : '해결'; ?>
                        </span>
                    </span>

                    <div class="right-float">
                        <div class="float-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" onclick="modifyMemo( this )">
                                <path d="M7 7.00012H6C5.46957 7.00012 4.96086 7.21084 4.58579 7.58591C4.21071 7.96098 4 8.46969 4 9.00012V18.0001C4 18.5306 4.21071 19.0393 4.58579 19.4143C4.96086 19.7894 5.46957 20.0001 6 20.0001H15C15.5304 20.0001 16.0391 19.7894 16.4142 19.4143C16.7893 19.0393 17 18.5306 17 18.0001V17.0001M16 5.00012L19 8.00012M20.385 6.58511C20.7788 6.19126 21.0001 5.65709 21.0001 5.10011C21.0001 4.54312 20.7788 4.00895 20.385 3.61511C19.9912 3.22126 19.457 3 18.9 3C18.343 3 17.8088 3.22126 17.415 3.61511L9 12.0001V15.0001H12L20.385 6.58511Z" stroke="#6C7A91" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="float-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" onclick="deleteMemoConfirm( this )" >
                            <path d="M4 7H20M10 11V17M14 11V17M5 7L6 19C6 19.5304 6.21071 20.0391 6.58579 20.4142C6.96086 20.7893 7.46957 21 8 21H16C16.5304 21 17.0391 20.7893 17.4142 20.4142C17.7893 20.0391 18 19.5304 18 19L19 7M9 7V4C9 3.73478 9.10536 3.48043 9.29289 3.29289C9.48043 3.10536 9.73478 3 10 3H14C14.2652 3 14.5196 3.10536 14.7071 3.29289C14.8946 3.48043 15 3.73478 15 4V7" stroke="#6C7A91" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        </div>
                        <div class="float-item">

                            <svg class="toggleSvg <?php echo _elm($aMemo, 'M_STATUS') !== 'READY' ? 'active' : ''; ?>"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <circle class="bg-circle" cx="12" cy="12" r="12" />
                                <path class="check-path" d="M17 3.33989C18.5083 4.21075 19.7629 5.46042 20.6398 6.96519C21.5167 8.46997 21.9854 10.1777 21.9994 11.9192C22.0135 13.6608 21.5725 15.3758 20.72 16.8946C19.8676 18.4133 18.6332 19.6831 17.1392 20.5782C15.6452 21.4733 13.9434 21.9627 12.2021 21.998C10.4608 22.0332 8.74055 21.6131 7.21155 20.7791C5.68256 19.9452 4.39787 18.7264 3.48467 17.2434C2.57146 15.7604 2.06141 14.0646 2.005 12.3239L2 11.9999L2.005 11.6759C2.061 9.94888 2.56355 8.26585 3.46364 6.79089C4.36373 5.31592 5.63065 4.09934 7.14089 3.25977C8.65113 2.42021 10.3531 1.98629 12.081 2.00033C13.8089 2.01437 15.5036 2.47589 17 3.33989ZM15.707 9.29289C15.5348 9.12072 15.3057 9.01729 15.0627 9.002C14.8197 8.98672 14.5794 9.06064 14.387 9.20989L14.293 9.29289L11 12.5849L9.707 11.2929L9.613 11.2099C9.42058 11.0607 9.18037 10.9869 8.9374 11.0022C8.69444 11.0176 8.46541 11.121 8.29326 11.2932C8.12112 11.4653 8.01768 11.6943 8.00235 11.9373C7.98702 12.1803 8.06086 12.4205 8.21 12.6129L8.293 12.7069L10.293 14.7069L10.387 14.7899C10.5624 14.926 10.778 14.9998 11 14.9998C11.222 14.9998 11.4376 14.926 11.613 14.7899L11.707 14.7069L15.707 10.7069L15.79 10.6129C15.9393 10.4205 16.0132 10.1802 15.9979 9.93721C15.9826 9.69419 15.8792 9.46509 15.707 9.29289Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="memoContent">
                <?php echo _elm($aMemo, 'M_CREATE_AT'); ?>
            </div>
            <div>
                <div><?php echo htmlspecialchars_decode(_elm($aMemo, 'M_CONTENT') );  ?></div>
            </div>

        </div>

    </div>
<?php
    endforeach;
}else{
?>
<div class="memo-empty">
    <div class="top-right-box">
        <div style="flex:9;">
            메모 없음
        </div>
        <div style="flex:2; font-size:14px; font-weight:600;text-align:right;">
            <svg xmlns="http://www.w3.org/2000/svg"
                width="24" height="24"
                viewBox="0 0 24 24"
                fill="none"
                style="cursor: pointer; pointer-events: all;"
                onclick="openMemoLayer('<?php echo $mOrdid; ?>', this)">
            <path d="M7 7.00012H6C5.46957 7.00012 4.96086 7.21084 4.58579 7.58591C4.21071 7.96098 4 8.46969 4 9.00012V18.0001C4 18.5306 4.21071 19.0393 4.58579 19.4143C4.96086 19.7894 5.46957 20.0001 6 20.0001H15C15.5304 20.0001 16.0391 19.7894 16.4142 19.4143C16.7893 19.0393 17 18.5306 17 18.0001V17.0001M16 5.00012L19 8.00012M20.385 6.58511C20.7788 6.19126 21.0001 5.65709 21.0001 5.10011C21.0001 4.54312 20.7788 4.00895 20.385 3.61511C19.9912 3.22126 19.457 3 18.9 3C18.343 3 17.8088 3.22126 17.415 3.61511L9 12.0001V15.0001H12L20.385 6.58511Z"
                    stroke="#6C7A91"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>

        </div>
    </div>
</div>
<?php
}
?>
