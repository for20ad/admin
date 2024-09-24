<?php
    helper(['form', 'owens_form']);
    $view_datas = $owensView->getViewDatas();
    $aData = _elm($view_datas, 'aData', []);
?>

<div class="s-popup-overlay" id="s-popup-overlay-<?php echo _elm($aData, 'P_IDX'); ?>" style="display:none;">
    <div class="s-popup <?php echo _elm($aData, 'P_LOCATE'); ?>" id="s-popup" style="z-index:100000;">
        <button class="s-popup-close" data-id="<?php echo _elm($aData, 'P_IDX'); ?>">×</button>
        <div class="s-popup-header">
            <?php echo _elm($aData, 'P_TITLE'); ?>
        </div>
        <?php echo empty(_elm($aData, 'P_LINK_URL')) === false ? '<a href="'._elm($aData, 'P_LINK_URL').'" target="_blank">' : ''; ?>
            <div class="s-popup-body" style="width:<?php echo _elm($aData, 'P_WIDTH'); ?>px !important; height:<?php echo _elm($aData, 'P_HEIGHT') ?>px !important;" >
                <?php echo htmlspecialchars_decode(_elm($aData, 'P_CONTENT')); ?>
            </div>
        <?php echo empty(_elm($aData, 'P_LINK_URL')) === false ? '</a>' : ''; ?>
        <?php if (_elm($aData, 'P_CLOSE_YN') === 'Y') { ?>
        <div class="s-popup-footer">
            <div class="s-popup-checkbox">
                <input type="checkbox" id="s-popup-dont-show-<?php echo _elm($aData, 'P_IDX'); ?>" />
                <label for="s-popup-dont-show-<?php echo _elm($aData, 'P_IDX'); ?>">하루 동안 열지 않기</label>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
