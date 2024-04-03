/**
 * NAP Plugin Frontend Scripts
 */
;(function($) {
    $(document).ready(function() {

        var _this = {
            
            // Init all data
            __construct: function() {

                // vars
                _this.mostSellingTabs = $('#most-selling-products-tabs');
                _this.mainSectionTabs = $('#ap-main-content-tabs');
                _this.reviewBlock = $('#ap-reviews-block');
                _this.howToUseElements = $('.ap-how-to-choose-type-inner');
                _this.loadMoreBtn = $('#prd_list_yoko_btn');
                _this.productListdiv = $('.ap-product-list-inner');
                _this.showProductDetailsBtn = $('.product-list-item .chira_btn');

            },
            
            // Call functions
            init: function() {
                _this.__construct();
                _this.mostSellingProducts();
                _this.mainSection();
                _this.productList();
                _this.comparisonTool();
            },
            
            productList: function() {
                _this.loadMoreBtn.on('click', function() {
                    _this.productListdiv.find('.dsp_non').slice(0,10).removeClass('dsp_non');
                    if ( ! _this.productListdiv.find('.dsp_non').length ) $(this).addClass('dsp_non');
                });
                
                _this.showProductDetailsBtn.on('click', function() {
                   $(this).closest('.product-list-item').find('.ap-product-list-details').toggleClass('show'); 
                });
            },
            
            comparisonTool: function() {

        		var chkNum = '';
        
        		
        		function reCount() { 
        		    chkNum = $('.check_detail input:checked').length; 
        		}
        
        		function listShow() { 
        		    if (!chkNum > 0) { 
        		        $('.comparison_overlay , .comparison_wrapCover').fadeOut().remove(); 
        		    } 
        		    
        		}
        
        		function itemNum() { 
        		    reCount(); $('.itemNum').text(chkNum);
        		}
        
        		function exeBtnShowHide() {
        			if (!$('.comparison_exeBtn').length) {
        				if (chkNum > 1) { 
        				    $('.comparison_partsBox').append('<span class="comparison_exeBtn">商品を比較する</span>');
        				}
        			} else { 
        			    if (chkNum < 2) { 
        			        $('.comparison_exeBtn').remove(); 
        			    } 
        			}
        		}
        
        		$('.check_detail input').on('click', function () {
        			if ($(this).prop('checked')) {
        
        				reCount();
        
        				var prdName = $(this).closest('.product-list-item').find('.title a').text();
        				var prdNameLink = $(this).closest('.product-list-item').find('.title a').attr('href');
        				var prdImg = $(this).closest('.product-list-item').find('.yoko_l img').attr('src');
        				var prdReviewLink = prdNameLink + '#reviews';
        				var prdReviewTxt = $(this).closest('.product-list-item').find('.review').html();
        				var prdSeibun = ($(this).closest('.product-list-item').find('.ap-product-list-details .active-ingredients').length) ? $(this).closest('.product-list-item').find('.ap-product-list-details .active-ingredients').html() : '';
        				// var bunrui = '';//$(this).closest('.product-list-item').find('.original').length;
        				// if (bunrui == 1) { var prdBunrui = '先発薬'; } else { var prdBunrui = 'ジェネリック'; }
        				var prdKouka = ($(this).closest('.product-list-item').find('.ap-product-list-details .pr_effect').length) ? $(this).closest('.product-list-item').find('.ap-product-list-details .pr_effect').text() : '';
        				var prdFukusayou = ($(this).closest('.product-list-item').find('.ap-product-list-details .pr_side_effect').length) ? $(this).closest('.product-list-item').find('.ap-product-list-details .pr_side_effect').text() : '';
        				var prdKakaku = $(this).closest('.product-list-item').find('.yoko_r .price').html();
        
        				listShow();
        
        				if (!$('.comparison_overlay').length > 0) {	
        					$('body').append('<div class="comparison_overlay"></div><div class="comparison_wrapCover"><div class="comparison_outWrap"><div class="comparison_wrap"><div class="comparison_partsBox"><span class="itemNum_box">比較する商品一覧（<span class="itemNum"></span>件）</span><span class="all_del">一覧から全てを削除</span></div></div></div></div>');
        				}
        				if (chkNum > 0) {
        					$('.comparison_overlay').fadeIn(200);
        					
        					$('.comparison_wrap').prepend('<dl class="comparison_box"><dt class="prdName"><a href="' + prdNameLink + '" target="_blank">' + prdName + '</a></dt><dd class="prdImg one_act"><a href="' + prdNameLink + '" target="_blank"><img src="' + prdImg + '" alt="' + prdName + '"></a></dd><dd class="prdBuy"><a href="' + prdNameLink + '" target="_blank" class="red_btn">購入する</a></dd><dd class="prdReview">'+ prdReviewTxt +'</dd><dd class="prdSeibun dd_line"><span>成分：</span>' + prdSeibun + '</dd><dd class="prdKouka dd_line"><span>効果：</span>' + prdKouka + '</dd><dd class="prdFukusayou dd_line"><span>副作用：</span>' + prdFukusayou + '</dd><dd class="prdKakaku dd_line"><span>価格：</span><span class="prdKakaku_price">' + prdKakaku + '</span></dd><dd class="one_del one_act">削除する</dd></dl>');
        
        					itemNum();
        					exeBtnShowHide();
        
        				} else {
        					$('.comparison_overlay').fadeOut().remove();
        				}
        
        			} else {
        				itemNum();
        				exeBtnShowHide();
        
        				var offChkName = $(this).closest('.product-list-item').find('.title a').text();
        				$('.comparison_box').each(function () {
        					var offListName = $(this).find('.prdName a').text();
        					if (offChkName == offListName) {
        						$(this).closest('.comparison_box').remove();
        					}
        				});
        				listShow();
        			}
        		});
        
        		
        		$('body').on('click', '.comparison_exeBtn', function () {
        			$('.comparison_overlay , .comparison_wrapCover').addClass('full');
        		});
        
        		
        		$('body').on('click', '.comparison_overlay', function () {
        			if ($(this).hasClass('full')) {
        				$(this).removeClass('full');
        				$('.comparison_wrapCover').removeClass('full');
        			}
        		});
        		
        		$('body').on('click', '.comparison_wrapCover.full', function (e) {
        			if (!$(e.target).closest('.comparison_box').length) {
        				$(this).removeClass('full');
        				$('.comparison_overlay , .comparison_wrapCover').removeClass('full');
        			}
        		});
        
        		
        		$('body').on('click', '.all_del', function () {
        			$('.comparison_overlay , .comparison_wrapCover').fadeOut().remove();
        			$('.check_detail input').prop('checked', false);
        		});
        
        		
        		$('body').on('click', '.one_del', function () {
        			var existFull = $(this).closest('.full').length;
        			reCount();
        			if (existFull == 1 && chkNum <= 2) {
        				alert('商品を比較するには2商品以上選択する必要があります。');
        				return false;
        			} else {
        				var delName = $(this).closest('.comparison_box').find('.prdName a').text();
        				$('.check_detail input').each(function () {
        					var delChkName = $(this).closest('.product-list-item').find('.title a').text();
        					if (delName == delChkName) {
        						$(this).prop('checked', false);
        					}
        					itemNum();
        					exeBtnShowHide();
        				});
        				$(this).closest('.comparison_box').remove();
        
        				listShow();
        			}
        		});
            
            },
            
            mainSection: function() {
                _this.mainSectionTabs.tabs();
                _this.reviewBlock.tabs();
                
                $(window).on('load resize', function(e){
                    if (_this.howToUseElements.length) {
                        if(window.innerWidth < 768) {
                            jQuery.each(_this.howToUseElements, function(k, v) {
                               let row_width = window.innerWidth - 40;
                               jQuery(v).css({'width': row_width + 'px'});
                               
                               let inner_width = jQuery(v).find('.how-to-choose-item').length * jQuery(v).find('.how-to-choose-item').innerWidth();
                               jQuery(v).find('.row').css({'width': inner_width + 'px'});
                            });
                        }
                        else {
                            jQuery.each(_this.howToUseElements, function(k, v) {
                                jQuery(v).css({'width': 'auto'});
                                jQuery(v).find('.row').css({'width': 'auto'});
                            });
                        }
                    }
                });
            },
            
            mostSellingProducts: function() {
                _this.mostSellingTabs.tabs();
                
                $(window).on('load resize', function(e){
                    if (_this.mostSellingTabs.length) {
                        if(window.innerWidth < 768) {
                            jQuery.each(_this.mostSellingTabs.children('div'), function(k, v) {
                               let row_width = window.innerWidth - 40;
                               jQuery(v).css({'width': row_width + 'px'});
                               
                               let inner_width = jQuery(v).find('.most-selling-products-item').length * jQuery(v).find('.most-selling-products-item').innerWidth();
                               jQuery(v).find('.row').css({'width': inner_width + 'px'});
                            });
                        }
                        else {
                            jQuery.each(_this.mostSellingTabs.children('div'), function(k, v) {
                                jQuery(v).css({'width': 'auto'});
                                jQuery(v).find('.row').css({'width': 'auto'});
                            });
                        }
                    }
                });
                
                // setTimeout(function() {
                //     $('#most-selling-products-tabs .row').slick({
                //         slidesToShow: 2,
                //         slidesToScroll: 1,
                //         mobileFirst: true,
                //         responsive: [
                //             {
                //                 breakpoint: 768,
                //                 settings: 'unslick'
                //             }
                //         ]
                //     });
                // }, 2000);
            },
        }
        
        _this.init();
    });
})(jQuery);