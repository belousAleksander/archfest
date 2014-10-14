app.directive('scrollProjects', function() {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {

            var dragger = element.find('div.scroll div.dragger'),
                scrollLine = element.find('div.scroll'),
                container = element.find('div.container'),
                containerWidth = container.outerWidth(),
                ul = element.find('div.container ul'),
                draggerCache = {},
                factor = 1;

            function calculateDraggerWidth() {
                var draggerWidth,
                    ulWidth = ul.outerWidth(true);
                factor = containerWidth / ulWidth;

                draggerWidth = scrollLine.width() * factor;

                if(draggerWidth > containerWidth) {
                    draggerWidth = containerWidth;
                }

                dragger.width(draggerWidth);
            }
            element.on('mousedown touchstart', function (e) {

                draggerCache.down = true;

                if (e.type === "touchstart") {
                    var touch = e.originalEvent.touches[0];
                    draggerCache.mouseStart = touch.clientX;
                } else {
                    draggerCache.mouseStart = e.pageX;
                }
                draggerCache.scrollLeftPosition = dragger.position().left;
            });

            dragger.on('mousedown touchstart', function (e) {

                draggerCache.down = true;

                if (e.type === "touchstart") {
                    var touch = e.originalEvent.touches[0];
                    draggerCache.mouseStart = touch.clientX;
                } else {
                    draggerCache.mouseStart = e.pageX;
                }
                draggerCache.scrollLeftPosition = dragger.position().left;
            });

            element.on('mousemove touchmove', function (e) {

                if (e.type === "touchmove") {
                    var touch = e.originalEvent.touches[0];
                    moveDragger(touch);
                }
                if(draggerCache.down) {
                    moveDragger(e);
                }

                e.preventDefault();
                e.stopPropagation();
            });

            function moveDragger(e) {
                var draggerPosition;
                draggerPosition = draggerCache.scrollLeftPosition + (e.pageX - draggerCache.mouseStart);
                if(draggerPosition) {
                    draggerPosition = checkDragPosition (draggerPosition);
                    setDraggerPosition (draggerPosition);
                }
            }

            function setDraggerPosition (draggerPosition) {
                var ulWidth = ul.outerWidth(true);

                dragger.css('left', draggerPosition + 'px');
                container.scrollLeft(draggerPosition / factor);
            }

            function checkDragPosition (draggerPosition) {

                if(draggerPosition < 0) {
                    draggerPosition = 0;
                }

                if(draggerPosition > scrollLine.width() - dragger.width()) {
                    draggerPosition = scrollLine.width() - dragger.width();
                }

                return draggerPosition;
            }

            element.on('mousewheel', function (event, delta) {
                var draggerPosition = container.scrollLeft(),
                    originalEvent = event.originalEvent;
                if (!delta) {
                    delta = originalEvent.wheelDelta;
                }
                if (delta > 0) {
                    draggerPosition = draggerPosition + 50;
                } else if (delta < 0) {
                    draggerPosition = draggerPosition - 50;
                }
                draggerPosition = checkDragPosition (draggerPosition * factor);
                setDraggerPosition (draggerPosition);
                return false;
            });

            $(document).on('selectstart dragstart', function(evt) {
                if(draggerCache.down) {
                    evt.preventDefault();
                    return false;
                }
            });

            $(document).on('mouseup touchend', function (e) {
                draggerCache = {};
            });
            $(window).load(function() {
                calculateDraggerWidth();
            });

            scrollLine.on('click', function (e) {
                var elementPosition = element.offset(),
                    draggerWidth = dragger.outerWidth(),
                    widthToDraggerCenter = draggerWidth / 2,
                    position = e.pageX - elementPosition.left - widthToDraggerCenter;
                position = checkDragPosition(position);
                setDraggerPosition(position);
            });
        }
    };
});