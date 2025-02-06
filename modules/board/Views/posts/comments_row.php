<?php
    helper(['owens', 'owens_form', 'owens_url']);
    $view_datas = $owensView->getViewDatas();

    $sQUERY_STRING = (empty($_SERVER['QUERY_STRING']) === true) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $sQUERY_STRING = _add_query_string($sQUERY_STRING, _elm($view_datas, 'query_string', []));

    $aLists = _elm($view_datas, 'lists', []);
    $aConfig = _elm($view_datas, 'aConfig', []);
    $row = _elm($view_datas, 'row', 0);
    $total_rows = _elm($view_datas, 'total_rows', 0);
    $aBoardId = _elm($view_datas, 'bo_id', '');

    if (!empty($aLists)):
        foreach ($aLists as $vData):
            $row++;
            $no = $total_rows - $row + 1;
?>

<div class="comment-item" style="margin-bottom: 15px; padding: 15px; background-color: #f0f8ff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <strong style="font-size: 14px;"><?php echo _elm($vData, 'C_WRITER_NAME'); ?></strong>
            <span style="font-size: 12px; color: #aaa;"><?php echo _elm($vData, 'C_REG_AT'); ?></span>
            <a href="javascript:void(0);" onclick="showReplyBox(<?php echo _elm($vData, 'C_IDX'); ?>)" style="font-size: 12px; color: #007bff; text-decoration: none; margin-left: 10px;">
                댓글입력
            </a>
            <?php
            if( _elm($vData, 'C_STATUS') == 1 ){
            ?>
            <a href="javascript:void(0);" onclick="chamgeReplyStatusConfirm(<?php echo _elm($vData, 'C_IDX'); ?>, 3)" style="font-size: 12px; color: red; text-decoration: none; margin-left: 10px;">
                삭제
            </a>
            <a href="javascript:void(0);" onclick="chamgeReplyStatusConfirm(<?php echo _elm($vData, 'C_IDX'); ?>, 9)" style="font-size: 12px; color: grey; text-decoration: none; margin-left: 10px;">
                블라인드 처리
            </a>
            <?php
            }else{
            ?>

            <a href="javascript:void(0);" onclick="chamgeReplyStatusConfirm(<?php echo _elm($vData, 'C_IDX'); ?>, 1)" style="font-size: 12px; color: black; text-decoration: none; margin-left: 10px;">
            현재 <?php echo _elm($vData, 'C_STATUS') == 3 ? '<span style="color:red">삭제</span>' : '<span style="color:grey">블라인드</span>'?> 상태입니다. 노출상태로 변경하실경우 클릭해주세요.
            </a>
            <?php
            }
            ?>

        </div>
    </div>
    <p style="margin: 10px 0 0 0; font-size: 14px; line-height: 1.5;"><?php echo nl2br(htmlspecialchars(_elm($vData, 'C_COMMENT'))); ?></p>

    <!-- 댓글 입력 박스 -->
    <div id="reply-box-<?php echo _elm($vData, 'C_IDX'); ?>" style="display: none; margin-top: 10px;">
        <textarea placeholder="댓글을 입력하세요..." id="reply-textarea-<?php echo _elm($vData, 'C_IDX'); ?>"  style="width: 100%; height: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;"></textarea>
        <div style="margin-top: 5px; text-align: right;">
            <button type="button" class="reply-submit-btn" data-c-idx="<?php echo _elm($vData, 'C_IDX'); ?>" style="background: #28a745; color: white; border: none; border-radius: 5px; padding: 5px 10px; font-size: 12px; cursor: pointer;">
                등록
            </button>
        </div>
    </div>
</div>

<?php
            // 자식 댓글 출력 함수 호출
            if (!empty(_elm($vData, 'children'))) {
                renderChildren(_elm($vData, 'children'), 1);
            }
        endforeach;
    else:
?>

<div class="no-comments" style="margin-top: 20px; font-size: 14px; text-align: center; color: #777;">
    댓글이 없습니다.
</div>

<?php
    endif;

    // 자식 댓글을 재귀적으로 렌더링하는 함수
    function renderChildren($children, $depth)
    {
        foreach ($children as $child) {
?>
    <div class="comment-item" style="margin: 10px 0 10px <?php echo $depth * 20; ?>px; padding: 15px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <strong style="font-size: 14px;"><?php echo _elm($child, 'C_WRITER_NAME'); ?></strong>
                <span style="font-size: 12px; color: #aaa;"><?php echo _elm($child, 'C_REG_AT'); ?></span>
                <a href="javascript:void(0);" onclick="showReplyBox(<?php echo _elm($child, 'C_IDX'); ?>)" style="font-size: 12px; color: #007bff; text-decoration: none; margin-left: 10px;">
                    댓글 입력
                </a>
                <?php
                if( _elm($child, 'C_STATUS') == 1 ){
                ?>
                <a href="javascript:void(0);" onclick="chamgeReplyStatusConfirm(<?php echo _elm($child, 'C_IDX'); ?>, 3)" style="font-size: 12px; color: red; text-decoration: none; margin-left: 10px;">
                    삭제
                </a>
                <a href="javascript:void(0);" onclick="chamgeReplyStatusConfirm(<?php echo _elm($child, 'C_IDX'); ?>, 9)" style="font-size: 12px; color: grey; text-decoration: none; margin-left: 10px;">
                    블라인드 처리
                </a>
                <?php
                }else{
                ?>

                <a href="javascript:void(0);" onclick="chamgeReplyStatusConfirm(<?php echo _elm($child, 'C_IDX'); ?>, 1)" style="font-size: 12px; color: black; text-decoration: none; margin-left: 10px;">
                현재 <?php echo _elm($child, 'C_STATUS') == 3 ? '<span style="color:red">삭제</span>' : '<span style="color:grey">블라인드</span>'?> 상태입니다. 노출상태로 변경하실경우 클릭해주세요.
                </a>
                <?php
                }
                ?>
            </div>
        </div>
        <p style="margin: 10px 0 0 0; font-size: 14px; line-height: 1.5;"><?php echo nl2br(htmlspecialchars(_elm($child, 'C_COMMENT'))); ?></p>

        <!-- 댓글 입력 박스 -->
        <div id="reply-box-<?php echo _elm($child, 'C_IDX'); ?>" style="display: none; margin-top: 10px;">
            <textarea placeholder="댓글을 입력하세요..." id="reply-textarea-<?php echo _elm($child, 'C_IDX'); ?>"  style="width: 100%; height: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;"></textarea>
            <div style="margin-top: 5px; text-align: right;">
                <button type="button" class="reply-submit-btn" data-c-idx="<?php echo _elm($child, 'C_IDX'); ?>" style="background: #28a745; color: white; border: none; border-radius: 5px; padding: 5px 10px; font-size: 12px; cursor: pointer;">
                    등록
                </button>
            </div>
        </div>
    </div>
<?php
            if (!empty(_elm($child, 'children'))) {
                renderChildren(_elm($child, 'children'), $depth + 1);
            }
        }
    }
?>
<script>

</script>
