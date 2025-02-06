<?php
helper(['form', 'owens_form']);
$view_datas = $owensView->getViewDatas();

// $groupedByDate 데이터가 있다고 가정합니다.
$groupedByDate = _elm($view_datas, 'lists', []);

// 그룹화된 데이터가 비어 있지 않을 경우 출력
if (!empty($groupedByDate)) {
    foreach ($groupedByDate as $date => $comments) {
        ?>
        <!-- 날짜 헤더 출력 -->
        <div style="margin: 20px 0; font-weight: bold; font-size: 16px;">
            <?php echo $date; ?> <!-- 날짜 표시 -->
        </div>
        <?php
        foreach ($comments as $comment) {
            ?>
            <div class="comment-item" style="margin: 10px 0; padding: 15px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <!-- 작성자 이름 -->
                        <strong style="font-size: 14px;"><?php echo htmlspecialchars(_elm($comment, 'C_WRITER_NAME')); ?></strong>
                        <!-- 작성 시간 -->
                        <span style="font-size: 12px; color: #aaa;"><?php echo date('H:i:s', strtotime(_elm($comment, 'C_CREATE_AT'))); ?></span>
                        <!-- 삭제 버튼 -->
                        <a href="javascript:void(0);" onclick="deleteCounselConfirm(<?php echo (int)_elm($comment, 'C_IDX'); ?>, 3)" style="font-size: 12px; color: red; text-decoration: none; margin-left: 10px;">
                            삭제
                        </a>
                    </div>
                </div>
                <!-- 댓글 내용 -->
                <p style="margin: 10px 0 0 0; font-size: 14px; line-height: 1.5;"><?php echo htmlspecialchars_decode(_elm($comment, 'C_CONTENT') ); ?>

            </p>
            </div>
            <?php
        }
    }
} else {
    // 댓글이 없는 경우 출력
    ?>
    <div class="comment-item" style="margin: 10px 0; padding: 15px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
        등록된 상담 내역이 없습니다.
    </div>
    <?php
}
?>
