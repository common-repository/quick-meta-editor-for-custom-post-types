jQuery(document).ready(function($) {

    $.post($('#ajax-url').val(), {action: 'cpt_meta_editor_main'},
        function(rep) {
            main_object = JSON.parse(rep);

            for (let i = 0; i < main_object.post_types.length; i++) {
                const t = main_object.post_types[i];
                $('#types-select').append($('<option>', {value: t.name, text: t.label}));
            }
        }
    );

    
});

function select_type() {
    jQuery(function($) {

        // clear content
        $('.cpt-meta-editor-content, .cpt-meta-editor-metas .metas').html('');
        $('.cpt-meta-editor-metas').hide();

        var t = $('#types-select').val();
        current_type = t;
        var data = main_object.data[t];
        if(t == '') return;

        for (let i = 0; i < data.meta.length; i++) {
            const meta = data.meta[i];
            $('.cpt-meta-editor-metas .metas').append(
                $('<div>', {class: 'form-check form-check-inline'}).append(
                    $('<input>', {
                        type: 'checkbox',
                        checked: 'checked',
                        value: meta,
                        class: 'meta-checkbox form-check-input',
                        id: 'meta_' + meta + i,
                        onchange: 'create_content()'
                    }),
                    $('<label>', {
                        class: 'form-check-label',
                        html: meta,
                        for: 'meta_' + meta + i
                    })
                )
            );
        }

        create_content();
        
        $('.custom-post-type').html(t);
        $('.custom-post-type-count').html(main_object.data[t].posts.length + ' ' + $('#entries-text').val());
        $('.cpt-meta-editor-metas').show();
    });
    
}

function create_content() {
    jQuery(function($) {
        // clear content
        $('.cpt-meta-editor-content').html('');

        var f = $('#filter').val();
        var t = current_type;
        var posts = main_object.data[t].posts;
        var metas = main_object.data[t].meta;

        for (let i = 0; i < posts.length; i++) {
            const post = posts[i];

            var display = false;
            if(f == '' && t !== null) display = true;
            if(post.title.includes(f)) display = true;
            $('.meta-checkbox').each(function() {
                var meta = $(this).val();
                if(post[meta] !== undefined && post[meta].includes(f)) display = true;
            });

            if(display) {
                $('.cpt-meta-editor-content').append($('<fieldset>', {
                    id: 'post-' + post.ID,
                    class: 'bg-light p-2 mb-4'
                }));
                $('#post-' + post.ID).append($('<legend>', {
                    class: 'post-title bg-info text-white text-center pl-1 pr-1',
                    html: post.title
                }));
    
                $('.meta-checkbox:checked').each(function() {
                    var meta = $(this).val();
                    $('#post-' + post.ID).append($('<div>', {
                        class: 'post-meta form-group'
                    }).append(
                        $('<label>', {
                            class: 'meta-title',
                            html: meta
                        }),
                        $('<input>', {
                            type: 'text',
                            value: post[meta],
                            'data-id': post.ID,
                            'data-meta': meta,
                            'data-order': i,
                            'data-type': t,
                            class: 'meta form-control',
                            onchange: 'change_meta(this)'
                        }),
                        $('<div>', {
                            class: 'valid-tooltip success-message',
                            html: $('#success-message').val()
                        })
                    ));
                });
            }
            
        }

    });
}

function change_meta(m) {
    jQuery(function($) {
        
        $.post($('#ajax-url').val(), {
            action: 'cpt_meta_editor_meta',
            nonce: $('#_wpnonce').val(),
            id: $(m).data('id'),
            meta: $(m).data('meta'),
            value: $(m).val()
        }, function(rep) {
            main_object.data[$(m).data('type')].posts[$(m).data('order')][$(m).data('meta')] = $(m).val();
            $(m).parent().find('.success-message').show().delay(1000).fadeOut(3000);
        });
    });
}

function filter_check() {
    jQuery(function($) {
        var f = $('#filter').val();
        create_content();
    });
    
}

var main_object = null;
var current_type = null;