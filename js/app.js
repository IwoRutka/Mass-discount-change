/**
 *
 * NOTICE OF LICENSE
 *
 *
 *  @author    Fotax <web@fotax.pl>
 *  @copyright 2021 Fotax
 *  @license   Fotax
 */
    
    $(function() {
        var $in = $('#masspricefree_value')
        $in.keyup(function() {
            $in.val($in.val().replace(/,/g,'.'))
        })
    })