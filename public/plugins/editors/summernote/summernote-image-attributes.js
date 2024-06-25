/* https://github.com/dsvllc/summernote-image-attributes */
/* 2020-02-05 15:17:36 - Archie - 수정 */
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        module.exports = factory(require('jquery'));
    } else {
        factory(window.jQuery);
    }
}(function ($) {
    var readFileAsDataURL = function (file) {
        return $.Deferred(function (deferred) {
            $.extend(new FileReader(), {
                onload: function (e) {
                    var sDataURL = e.target.result;
                    deferred.resolve(sDataURL);
                },
                onerror: function () {
                    deferred.reject(this);
                }
            }).readAsDataURL(file);
        }).promise();
    };
    $.extend(true, $.summernote.lang, {
        'en-US': {
            /* US English(Default Language) */
            imageAttributes: {
                dialogTitle: 'Image Attributes',
                tooltip: 'Image Attributes',
                tabImage: 'Image',
                src: 'Source',
                browse: 'Browse',
                title: 'Title',
                alt: 'Alt Text',
                dimensions: 'Dimensions',
                tabAttributes: 'Attributes',
                class: 'Class',
                style: 'Style',
                role: 'Role',
                tabLink: 'Link',
                linkHref: 'URL',
                linkTarget: 'Target',
                linkTargetInfo: 'Options: _self, _blank, _top, _parent',
                linkClass: 'Class',
                linkStyle: 'Style',
                linkRel: 'Rel',
                linkRelInfo: 'Options: alternate, author, bookmark, help, license, next, nofollow, noreferrer, prefetch, prev, search, tag',
                linkRole: 'Role',
                tabUpload: 'Upload',
                upload: 'Upload',
                tabBrowse: 'Browse',
                editBtn: 'OK'
            }
        }
    });
    $.extend(true, $.summernote.options, {
        imageAttributesIcon: '<i class="note-icon-pencil"/>',
        imageAttributesRemoveEmpty: true
    });
    $.extend($.summernote.plugins, {
        'imageAttributes': function (context) {
            var self = this,
                ui = $.summernote.ui,
                $note = context.layoutInfo.note,
                $editor = context.layoutInfo.editor,
                $editable = context.layoutInfo.editable,
                options = context.options,
                lang = options.langInfo
            context.memo('button.imageAttributes', function () {
                var button = ui.button({
                    contents: options.imageAttributesIcon,
                    container: options.container,
                    tooltip: lang.imageAttributes.tooltip,
                    click: function () {
                        context.invoke('imageAttributes.show');
                    }
                });
                return button.render();
            });
            this.initialize = function () {
                var $container = options.dialogsInBody ? $(document.body) : $editor;
                var body = '';

                body += '<input class="note-imageAttributes-src" type="hidden" />';
                body += '<input class="note-imageAttributes-width" type="hidden" />';
                body += '<input class="note-imageAttributes-height" type="hidden" />';

                body += '<input class="note-imageAttributes-class" type="hidden">';
                body += '<input class="note-imageAttributes-style" type="hidden">';
                body += '<input class="note-imageAttributes-role" type="hidden">';

                body += '<input class="note-imageAttributes-link-class" type="hidden">';
                body += '<input class="note-imageAttributes-link-style" type="hidden">';
                body += '<input class="note-imageAttributes-link-rel" type="hidden">';
                body += '<input class="note-imageAttributes-link-role" type="hidden">';


                body += '<div class="note-form-group note-group-imageAttributes-title">' +
                        '    <label class="note-form-label">' + lang.imageAttributes.title + '</label>' +
                        '    <div class="note-input-group">' +
                        '        <input class="note-imageAttributes-title note-form-control note-input" type="text" />' +
                        '    </div>' +
                        '</div>';
                body += '<div class="note-form-group note-group-imageAttributes-alt">' +
                        '    <label class="note-form-label">' + lang.imageAttributes.alt + '</label>' +
                        '    <div class="note-input-group">' +
                        '        <input class="note-imageAttributes-alt note-form-control note-input" type="text" />' +
                        '    </div>' +
                        '</div>';
                body += '<div class="note-form-group note-group-imageAttributes-link-href">' +
                        '    <label class="note-form-label">' + lang.imageAttributes.linkHref + '</label>' +
                        '    <div class="note-input-group">' +
                        '        <input class="note-imageAttributes-link-href note-form-control note-input" type="text" placeholder="https://">' +
                        '    </div>' +
                        '</div>';
                body += '<div class="note-form-group note-group-imageAttributes-link-target">' +
                        '    <label class="note-form-label">' + lang.imageAttributes.linkTarget + '</label>' +
                        '    <div class="note-input-group">' +
                        '        <select class="note-imageAttributes-link-target note-form-control">' +
                        '        <option></option>' +
                        '        <option>_blank</option>' +
                        '        <option>_parent</option>' +
                        '        <option>_self</option>' +
                        '        <option>_top</option>' +
                        '        </select>' +
                        '    </div>' +
                        '</div>';

                this.$dialog = ui.dialog({
                    title: lang.imageAttributes.dialogTitle,
                    body: body,
                    footer: '<button href="#" class="note-btn note-btn-primary note-imageAttributes-btn">' + lang.imageAttributes.editBtn + '</button>'
                }).render().appendTo($container);
            };
            this.destroy = function () {
                ui.hideDialog(this.$dialog);
                this.$dialog.remove();
            };
            this.bindEnterKey = function ($input, $btn) {
                $input.on('keypress', function (e) {
                    if (e.keyCode === 13) $btn.trigger('click');
                });
            };
            this.show = function () {
                var $img = $($editable.data('target'));
                var imgInfo = {
                    imgDom: $img,
                    title: $img.attr('title'),
                    src: $img.attr('src'),
                    alt: $img.attr('alt'),
                    width: $img.attr('width'),
                    height: $img.attr('height'),
                    role: $img.attr('role'),
                    class: $img.attr('class'),
                    style: $img.attr('style'),
                    imgLink: $($img).parent().is("a") ? $($img).parent() : null
                };
                this.showImageAttributesDialog(imgInfo).then(function (imgInfo) {
                    ui.hideDialog(self.$dialog);
                    var $img = imgInfo.imgDom;
                    if (options.imageAttributesRemoveEmpty) {
                        if (imgInfo.alt) $img.attr('alt', imgInfo.alt); else $img.removeAttr('alt');
                        if (imgInfo.width) $img.attr('width', imgInfo.width); else $img.removeAttr('width');
                        if (imgInfo.height) $img.attr('height', imgInfo.height); else $img.removeAttr('height');
                        if (imgInfo.title) $img.attr('title', imgInfo.title); else $img.removeAttr('title');
                        if (imgInfo.src) $img.attr('src', imgInfo.src); else $img.attr('src', '#');
                        if (imgInfo.class) $img.attr('class', imgInfo.class); else $img.removeAttr('class');
                        if (imgInfo.style) $img.attr('style', imgInfo.style); else $img.removeAttr('style');
                        if (imgInfo.role) $img.attr('role', imgInfo.role); else $img.removeAttr('role');
                    } else {
                        if (imgInfo.src) $img.attr('src', imgInfo.src); else $img.attr('src', '#');
                        $img.attr('alt', imgInfo.alt);
                        $img.attr('width', imgInfo.width);
                        $img.attr('height', imgInfo.height);
                        $img.attr('title', imgInfo.title);
                        $img.attr('class', imgInfo.class);
                        $img.attr('style', imgInfo.style);
                        $img.attr('role', imgInfo.role);
                    }
                    if ($img.parent().is("a")) $img.unwrap();
                    if (imgInfo.linkHref) {
                        var linkBody = '<a';
                        if (imgInfo.linkClass) linkBody += ' class="' + imgInfo.linkClass + '"';
                        if (imgInfo.linkStyle) linkBody += ' style="' + imgInfo.linkStyle + '"';
                        linkBody += ' href="' + imgInfo.linkHref + '" target="' + imgInfo.linkTarget + '"';
                        if (imgInfo.linkRel) linkBody += ' rel="' + imgInfo.linkRel + '"';
                        if (imgInfo.linkRole) linkBody += ' role="' + imgInfo.linkRole + '"';
                        linkBody += '></a>';
                        $img.wrap(linkBody);
                    }
                    $note.val(context.invoke('code'));
                    $note.change();
                });
            };
            this.showImageAttributesDialog = function (imgInfo) {
                return $.Deferred(function (deferred) {
                    var $imageTitle = self.$dialog.find('.note-imageAttributes-title'),
                        $imageInput = self.$dialog.find('.note-imageAttributes-input'),
                        $imageSrc = self.$dialog.find('.note-imageAttributes-src'),
                        $imageAlt = self.$dialog.find('.note-imageAttributes-alt'),
                        $imageWidth = self.$dialog.find('.note-imageAttributes-width'),
                        $imageHeight = self.$dialog.find('.note-imageAttributes-height'),
                        $imageClass = self.$dialog.find('.note-imageAttributes-class'),
                        $imageStyle = self.$dialog.find('.note-imageAttributes-style'),
                        $imageRole = self.$dialog.find('.note-imageAttributes-role'),
                        $linkHref = self.$dialog.find('.note-imageAttributes-link-href'),
                        $linkTarget = self.$dialog.find('.note-imageAttributes-link-target'),
                        $linkClass = self.$dialog.find('.note-imageAttributes-link-class'),
                        $linkStyle = self.$dialog.find('.note-imageAttributes-link-style'),
                        $linkRel = self.$dialog.find('.note-imageAttributes-link-rel'),
                        $linkRole = self.$dialog.find('.note-imageAttributes-link-role'),
                        $editBtn = self.$dialog.find('.note-imageAttributes-btn');
                    $linkHref.val();
                    $linkClass.val();
                    $linkStyle.val();
                    $linkRole.val();
                    $linkTarget.val();
                    $linkRel.val();
                    if (imgInfo.imgLink) {
                        $linkHref.val(imgInfo.imgLink.attr('href'));
                        $linkClass.val(imgInfo.imgLink.attr('class'));
                        $linkStyle.val(imgInfo.imgLink.attr('style'));
                        $linkRole.val(imgInfo.imgLink.attr('role'));
                        $linkTarget.val(imgInfo.imgLink.attr('target'));
                        $linkRel.val(imgInfo.imgLink.attr('rel'));
                    }
                    ui.onDialogShown(self.$dialog, function () {
                        context.triggerEvent('dialog.shown');
                        $imageInput.replaceWith(
                            $imageInput.clone().on('change', function () {
                                var callbacks = options.callbacks;
                                if (callbacks.onImageUpload) {
                                    context.triggerEvent('image.upload', this.files[0]);
                                } else {
                                    readFileAsDataURL(this.files[0]).then(function (dataURL) {
                                        $imageSrc.val(dataURL);
                                    }).fail(function () {
                                        context.triggerEvent('image.upload.error');
                                    });
                                }
                            }).val('')
                        );
                        $editBtn.click(function (e) {
                            e.preventDefault();
                            deferred.resolve({
                                imgDom: imgInfo.imgDom,
                                title: $imageTitle.val(),
                                src: $imageSrc.val(),
                                alt: $imageAlt.val(),
                                width: $imageWidth.val(),
                                height: $imageHeight.val(),
                                class: $imageClass.val(),
                                style: $imageStyle.val(),
                                role: $imageRole.val(),
                                linkHref: $linkHref.val(),
                                linkTarget: $linkTarget.val(),
                                linkClass: $linkClass.val(),
                                linkStyle: $linkStyle.val(),
                                linkRel: $linkRel.val(),
                                linkRole: $linkRole.val()
                            }).then(function() {
                                context.triggerEvent('change', $editable.html());
                            })
                        });
                        $imageTitle.val(imgInfo.title);
                        $imageSrc.val(imgInfo.src);
                        $imageAlt.val(imgInfo.alt);
                        $imageWidth.val(imgInfo.width);
                        $imageHeight.val(imgInfo.height);
                        $imageClass.val(imgInfo.class);
                        $imageStyle.val(imgInfo.style);
                        $imageRole.val(imgInfo.role);
                        self.bindEnterKey($editBtn);
                    });
                    ui.onDialogHidden(self.$dialog, function () {
                        $editBtn.off('click');
                        if (deferred.state() === 'pending') deferred.reject();
                    });
                    ui.showDialog(self.$dialog);
                });
            };
        }
    });
}));
