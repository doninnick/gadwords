define([
    'jquery',
    'underscore',
    'jquery-ui-modules/widget'
], function ($, _) {
    'use strict';

    return function (SwatchRenderer) {
        $.widget('mage.SwatchRenderer', SwatchRenderer, {

            _OnClick: function ($this, widget) {
                if (!_.isUndefined(window.google_tag_params)) {
                    if (window.google_tag_params.ecomm_pagetype === 'product') {
                        var productVariationPrices = this.options.jsonConfig.optionPrices;
                        var productVariationsSku = this.options.jsonConfig.sku;
                        this._super($this, widget);
                        var productSku = productVariationsSku[widget.getProductId()];
                        var productPrice = productVariationPrices[widget.getProductId()]

                        if (!_.isUndefined(productSku) && productSku !== null && !_.isUndefined(productPrice)) {
                            gtag('event', 'page_view', {
                                ecomm_pagetype: 'product',
                                ecomm_prodid: productSku,
                                ecomm_totalvalue: productPrice.finalPrice.amount,
                            });
                        }
                    }
                }
            }

        });

        return $.mage.SwatchRenderer;
    };
});
