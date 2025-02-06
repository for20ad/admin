<?php
    helper( ['form', 'owens_form'] );
    $view_datas = $owensView->getViewDatas();


    $sQueryString     = ( empty($_SERVER['QUERY_STRING']) === true ) ? '' : '?' . $_SERVER['QUERY_STRING'];
    $aMbIdx           = _elm( $view_datas, 'memIdx' );
?>

<div style="overflow-y: scroll;" id="scrollContainer">
    <div style="max-height: 600px;" id="commentLists"></div>
</div>
<?php echo form_open('', ['method' => 'post', 'class' => '', 'id' => 'frm_counsel_register', 'autocomplete' => 'off']); ?>
<input type="hidden" name="memIdx" value="<?php echo $aMbIdx?>">
<div id="reply-box" style=" margin-top: 10px;">
        <textarea placeholder="상담내역을 입력하세요..." name="content" id="reply-textarea"  style="width: 100%; height: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;"></textarea>
        <div style="margin-top: 5px; text-align: right;">
            <button type="button"  class="reply-submit-btn" style="background: #28a745; color: white; border: none; border-radius: 5px; padding: 5px 10px; font-size: 12px; cursor: pointer;">
                등록
            </button>
        </div>
    </div>
<?php echo form_close();?>


<script>
var mbIdx = <?php echo $aMbIdx?>;
var isCalling = false;

getCounselLists( mbIdx );
$('.reply-submit-btn').off('click').on('click', function () {

    var counselfrm = $('#frm_counsel_register');
    if( counselfrm.find('[name=content]').val().trim() == '' ){
        box_alert('상담내역을 입력해주세요.','e');
        return false;
    }

    var inputs = counselfrm.find('input, button, select');


    $.ajax({
        url: '/apis/membership/insertCounsel',
        type: 'post',
        data: counselfrm.serialize(),
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
            inputs.prop('disabled', true);
        },
        success: function (response) {
            $('#preloader').hide();
            inputs.prop('disabled', false);
            if (response.status === 'false') {
                let error_message = error_lists.join('<br />');
                if (error_message !== '') {
                    box_alert(error_message, 'e');
                }
                return;
            }
            counselfrm.find('[name=content]').val('');
            getCounselLists( mbIdx );

        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#preloader').hide();
            inputs.prop('disabled', false);
            console.error(textStatus);
        },
        complete: function () {
            isCalling = false;
        },
    });
});
function deleteCounselConfirm( c_idx ){
    box_confirm( '상담내역을 삭제 하시겠습니까?', 'q', '', deleteCounsel, c_idx );
}
function deleteCounsel( c_idx ){
    $.ajax({
        url: '/apis/membership/deleteCounsel',
        type: 'post',
        data: 'c_idx='+c_idx,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        success: function (response) {
            $('#preloader').hide();
            if (response.status === 'false') {
                let error_message = error_lists.join('<br />');
                if (error_message !== '') {
                    box_alert(error_message, 'e');
                }
                return;
            }

            getCounselLists( mbIdx, 'none' );


        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#preloader').hide();
            console.error(textStatus);
        },
        complete: function () {
            isCalling = false;
        },
    });
}
function getCounselLists(mb_idx, nonScrollFlag) {
    if (isCalling) return;
    isCalling = true;

    let data = 'memIdx=' + mb_idx;
    let url = '/apis/membership/getCounselListsRow';

    $.ajax({
        url: url,
        type: 'post',
        data: data,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#preloader').show();
        },
        success: function (response) {
            $('#preloader').hide();
            if (response.status === 'false') {
                let error_message = error_lists.join('<br />');
                if (error_message !== '') {
                    box_alert(error_message, 'e');
                }
                return;
            }

            $('#commentLists').empty().html(response.page_datas.rows);
            console.log( nonScrollFlag );
            if( nonScrollFlag != 'none' ){
                // 스크롤을 맨 아래로 이동
                setTimeout(function(){
                    const $commentLists = $('#scrollContainer');
                    $commentLists.animate({ scrollTop: $commentLists.prop('scrollHeight') }, 300); // 300ms 애니메이션
                }, 300);
            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#preloader').hide();
            console.error(textStatus);
        },
        complete: function () {
            isCalling = false;
        },
    });
}

</script>
