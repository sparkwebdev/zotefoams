jQuery(document).ready(function($) {
    'use strict';
    
    // Function to initialize a single interactive image instance
    function initializeInteractiveImage($wrapper) {
        // Find elements within this specific wrapper
        var $imageField = $wrapper.find('[data-name="interactive_image_bg"]');
        var $pointsField = $wrapper.find('[data-name="interactive_image_points"]');
        
        if (!$imageField.length || !$pointsField.length) {
            return;
        }
        
        var $imageUploader = $imageField.find('.acf-image-uploader');
        var $imageWrap = $imageUploader.find('.image-wrap');
        var $img = $imageWrap.find('img');
        
        if (!$img.length) {
            return;
        }
        
        // Remove any existing overlay
        $imageWrap.find('.interactive-image-overlay').remove();
        
        // Ensure image wrap is positioned
        $imageWrap.css('position', 'relative');
        
        // Create and add overlay
        var $overlay = $('<div class="interactive-image-overlay"></div>');
        $imageWrap.append($overlay);
        
        // Handle clicks on overlay
        $overlay.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Calculate position relative to image
            var imageOffset = $img.offset();
            var clickX = e.pageX - imageOffset.left;
            var clickY = e.pageY - imageOffset.top;
            var imageWidth = $img.width();
            var imageHeight = $img.height();
            
            // Convert to percentages
            var percentX = (clickX / imageWidth * 100);
            var percentY = (clickY / imageHeight * 100);
            
            // Check if click is near any existing marker
            var tooClose = false;
            var threshold = 3; // 3% distance threshold
            
            // Get all existing point positions from the repeater rows
            var $rows = $pointsField.find('.acf-repeater tbody > tr.acf-row').not('.acf-clone');
            
            $rows.each(function() {
                var $row = $(this);
                if ($row.hasClass('acf-row-removed')) {
                    return;
                }
                
                var existingLeft = parseFloat($row.find('[data-name="from_left"] input').val());
                var existingTop = parseFloat($row.find('[data-name="from_top"] input').val());
                
                if (!isNaN(existingLeft) && !isNaN(existingTop)) {
                    var distance = Math.sqrt(
                        Math.pow(percentX - existingLeft, 2) + 
                        Math.pow(percentY - existingTop, 2)
                    );
                    
                    if (distance < threshold) {
                        tooClose = true;
                        return false; // Break the loop
                    }
                }
            });
            
            if (tooClose) {
                // Flash the overlay red briefly to indicate too close
                $overlay.css('background-color', 'rgba(255, 0, 0, 0.2)');
                setTimeout(function() {
                    $overlay.css('background-color', 'rgba(0, 0, 0, 0)');
                }, 200);
                return; // Don't create a new marker
            }
            
            // Round to 1 decimal place
            percentX = percentX.toFixed(1);
            percentY = percentY.toFixed(1);
            
            // Find the add row button
            var $repeater = $pointsField.find('.acf-repeater');
            var $addButton = $repeater.find('.acf-actions .acf-button[data-event="add-row"]');
            
            if (!$addButton.length) {
                return;
            }
            
            // Temporarily disable marker updates
            $wrapper.addClass('adding-point');
            
            // Click the add button
            $addButton.trigger('click');
            
            // Wait for the new row to appear
            setTimeout(function() {
                // Find the newest row (last non-clone row)
                var $newRow = $repeater.find('tbody > tr.acf-row').not('.acf-clone').last();
                
                if (!$newRow.length) {
                    return;
                }
                
                // Set the position values
                var $leftInput = $newRow.find('[data-name="from_left"] input[type="number"]');
                var $topInput = $newRow.find('[data-name="from_top"] input[type="number"]');
                
                if ($leftInput.length && $topInput.length) {
                    $leftInput.val(percentX).trigger('change');
                    $topInput.val(percentY).trigger('change');
                }
                
                // Update markers only after position is set
                updateMarkers($wrapper);
                
                // Focus content field without scrolling
                var $contentTextarea = $newRow.find('[data-name="point_content"] textarea');
                if ($contentTextarea.length) {
                    // Save current scroll position
                    var scrollTop = $(window).scrollTop();
                    
                    // Focus the field
                    $contentTextarea.focus();
                    
                    // Restore scroll position
                    $(window).scrollTop(scrollTop);
                }
                
                // Re-enable marker updates
                $wrapper.removeClass('adding-point');
                
            }, 500); // Increased delay for row creation
        });
        
        // Initial marker update
        updateMarkers($wrapper);
        
        // Update markers on field changes
        $pointsField.on('change keyup', 'input, select, textarea', function() {
            // Skip updates while adding a new point
            if (!$wrapper.hasClass('adding-point')) {
                updateMarkers($wrapper);
            }
        });
        
        // Update markers when output numbers checkbox changes
        var $outputNumbersField = $wrapper.find('[data-name="interactive_image_output_numbers"] input[type="checkbox"]');
        $outputNumbersField.on('change', function() {
            updateMarkers($wrapper);
        });
    }
    
    // Function to update point markers
    function updateMarkers($wrapper) {
        var $imageWrap = $wrapper.find('[data-name="interactive_image_bg"] .image-wrap');
        var $pointsField = $wrapper.find('[data-name="interactive_image_points"]');
        
        // Check if output numbers is enabled
        var $outputNumbersField = $wrapper.find('[data-name="interactive_image_output_numbers"] input[type="checkbox"]');
        var outputNumbers = $outputNumbersField.is(':checked');
        
        // Remove existing markers
        $imageWrap.find('.interactive-point-marker').remove();
        
        // Get all rows
        var $rows = $pointsField.find('.acf-repeater tbody > tr.acf-row').not('.acf-clone');
        
        $rows.each(function(index) {
            var $row = $(this);
            
            // Skip removed rows
            if ($row.hasClass('acf-row-removed')) {
                return;
            }
            
            // Get values
            var leftVal = $row.find('[data-name="from_left"] input').val();
            var topVal = $row.find('[data-name="from_top"] input').val();
            
            // Skip if position values are not set
            if (!leftVal || !topVal) {
                return;
            }
            
            var left = parseFloat(leftVal);
            var top = parseFloat(topVal);
            
            // Determine marker type based on global setting
            var type = outputNumbers ? 'numbered' : 'circle';
            var number = index + 1;
            
            // Create marker
            var $marker = $('<div class="interactive-point-marker interactive-point-marker--' + type + '"></div>');
            
            $marker.css({
                position: 'absolute',
                left: left + '%',
                top: top + '%'
            });
            
            // Add number attribute to both types for admin reference
            $marker.attr('data-number', number);
            
            // Add to image wrap
            $imageWrap.append($marker);
        });
    }
    
    // Function to scan and initialize all interactive images
    function scanAndInitialize() {
        // Look for interactive image layouts
        $('.acf-flexible-content .layout').each(function() {
            var $layout = $(this);
            
            // Check if this is an interactive image layout
            if ($layout.find('[data-name="interactive_image_bg"]').length && 
                $layout.find('[data-name="interactive_image_points"]').length) {
                
                // Check if already initialized
                if (!$layout.hasClass('interactive-image-initialized')) {
                    $layout.addClass('interactive-image-initialized');
                    initializeInteractiveImage($layout);
                }
            }
        });
    }
    
    // Initial scan
    setTimeout(scanAndInitialize, 1000);
    
    // Re-scan when flexible content changes
    $(document).on('click', '.acf-flexible-content .acf-button', function() {
        setTimeout(scanAndInitialize, 1000);
    });
    
    // Re-scan when images change
    $(document).on('change', '.acf-image-uploader input[type="hidden"]', function() {
        var $layout = $(this).closest('.layout');
        if ($layout.find('[data-name="interactive_image_bg"]').length) {
            setTimeout(function() {
                $layout.removeClass('interactive-image-initialized');
                scanAndInitialize();
            }, 500);
        }
    });
    
    // Also initialize on ACF ready if available
    if (typeof acf !== 'undefined') {
        acf.addAction('ready', function() {
            setTimeout(scanAndInitialize, 500);
        });
    }
});