/**
 * Created by oleksandr on 7/2/14.
 */
app.directive('gallery', function() {
    var lock = false,
        timeOut = true,
        cache = {};
    return {
        restrict: 'C',
        link: function (scope, element, attrs) {
            function isImageLoaded(img) {
                if(typeof img.complete != 'undefined' && !img.complete) {
                    return false;
                }
                if(typeof img.naturalWidth != 'undefined' && img.naturalWidth == 0) {
                    return false;
                }
                return true;
            }

            function initButton() {
                var forward = angular.element('<div class="ad-forward"></div>'),
                    back = angular.element('<div class="ad-back"></div>'),
                    adNav = element.find('.ad-nav'),
                    thumb = adNav.find('.ad-thumbs'),
                    ul = adNav.find('ul'),
                    adNavLi = ul.find('li'),
                    images = adNavLi.find('img'),
                    width = 0;
                images.each(function (key) {
                    var img = angular.element(this);
                    // Check if the thumb has already loaded
                    if(!isImageLoaded(img[0])) {
                        img.load(
                            function() {
                                width += this.parentNode.parentNode.offsetWidth;
                                ul.width(width);
                            }
                        );
                    } else{
                        width += img[0].parentNode.parentNode.offsetWidth;
                        ul.width(width);
                    }

                });
                forward.add(back).css('opacity', 0.3);
                adNav.append(forward);
                adNav.prepend(back);

                function move(cElement, step, time) {
                    if(!lock) {
                        if(cElement.is('.ad-forward')) {
                            nextImage(step, time);
                        } else {
                            prevImage(step, time);
                        }
                    }

                    if(timeOut) {
                        setTimeout(function() {
                            move(cElement, step, time);
                        }, time + 2000);
                    }
                }
                forward.add(back).mouseover(
                    function(e) {
                        var cElement = angular.element(this);
                        cElement.css('opacity', 0.6);
                        cElement.find('div').show();

                        if(lock) {
                            return;
                        }
                        timeOut = true;

                        move(cElement, 1, 500);
                    }
                ).mouseout(

                    function(e) {

                        var cElement = angular.element(this);
                        cElement.css('opacity', 0.3);
                        timeOut = false;
                        angular.element(this).find('div').hide();
                    }
                ).click(
                    function() {
                        if(lock) {
                            return;
                        }

                        timeOut = false;
                        move(angular.element(this), 3, 800);

                        return false;
                    }
                ).find('div').css('opacity', 0.1);
            }
            angular.element(document).ready(function () {

                initButton();
            });

            element.on('touchstart', function (e) {
                var touch = e.originalEvent.touches[0];
                cache.touchStart = touch.clientX;
            });

            element.on('touchmove', function (e) {
                if(lock) {
                    return;
                }
                if (e.type === "touchmove") {
                    var touch = e.originalEvent.touches[0];
                    if(cache.touchStart > touch.clientX) {
                        nextImage(1, 200);
                    }else{
                        prevImage(1, 200);
                    }
                }

                e.preventDefault();
                e.stopPropagation();
            });


            function nextImage(step, time) {
                var
                    ul = element.find('ul'),
                    width = ul.width(),
                    lies = ul.find('li'),
                    nextLies = lies.slice(0, step),
                    html = '', totalWidthLi = 0;
                    angular.forEach(nextLies, function (cLi) {
                        var li = angular.element(cLi),
                            widthLi = li.width();
                        totalWidthLi = totalWidthLi + +widthLi;
                        html = html + '<li>' + li.html() + '</li>';
                    });
                ul.width(width + totalWidthLi);
                lock = true;
                element.find('ul').append(html);

                ul.animate({
                    marginLeft:  '-' + totalWidthLi + 'px'}, time, function() {
                    ul.css('margin-left', 0);
                    nextLies.remove();

                    lock = false;
                });
            }



            function prevImage(step, time) {
                var
                    ul = element.find('ul'),
                    width = ul.width(),
                    lies = ul.find('li'),
                    nextLies = lies.slice(-step, lies.length),
                    html = '', totalWidthLi = 0;

                angular.forEach(nextLies, function (cLi) {
                    var li = angular.element(cLi),
                        widthLi = li.width();
                    totalWidthLi = totalWidthLi + +widthLi;
                    html = html + '<li>' + li.html() + '</li>';
                });
                ul.width(width + totalWidthLi);
                lock = true;
                ul.css('margin-left', '-' + totalWidthLi + 'px');
                element.find('ul').prepend(html);
                ul.animate({
                    marginLeft:  0}, time, function() {
                    nextLies.remove();
                    lock = false;
                });
            }
        }
    };
});
