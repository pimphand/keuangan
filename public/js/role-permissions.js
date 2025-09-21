$(document).ready(function () {
    console.log('Role permissions script loaded');

    // Select All Permissions
    $('#selectAllPermissions').click(function () {
        console.log('Select all clicked');
        $('input[name="permissions[]"]').prop('checked', true);
        updateGroupButtonStates();
    });

    // Deselect All Permissions
    $('#deselectAllPermissions').click(function () {
        console.log('Deselect all clicked');
        $('input[name="permissions[]"]').prop('checked', false);
        updateGroupButtonStates();
    });

    // Select Group Permissions
    $('.select-group').click(function () {
        console.log('Select group clicked');
        var $card = $(this).closest('.card');
        $card.find('input[name="permissions[]"]').prop('checked', true);
        updateGroupButtonStates();
    });

    // Deselect Group Permissions
    $('.deselect-group').click(function () {
        console.log('Deselect group clicked');
        var $card = $(this).closest('.card');
        $card.find('input[name="permissions[]"]').prop('checked', false);
        updateGroupButtonStates();
    });

    // Update group button states based on current selection
    function updateGroupButtonStates() {
        console.log('Updating button states');

        $('.card').each(function () {
            var $card = $(this);
            var $checkboxes = $card.find('input[name="permissions[]"]');
            var checkedCount = $checkboxes.filter(':checked').length;
            var totalCount = $checkboxes.length;

            var $selectBtn = $card.find('.select-group');
            var $deselectBtn = $card.find('.deselect-group');

            if (checkedCount === 0) {
                $selectBtn.removeClass('btn-outline-primary').addClass('btn-primary');
                $deselectBtn.removeClass('btn-secondary').addClass('btn-outline-secondary');
            } else if (checkedCount === totalCount) {
                $selectBtn.removeClass('btn-primary').addClass('btn-outline-primary');
                $deselectBtn.removeClass('btn-secondary').addClass('btn-secondary');
            } else {
                $selectBtn.removeClass('btn-primary').addClass('btn-outline-primary');
                $deselectBtn.removeClass('btn-secondary').addClass('btn-outline-secondary');
            }
        });

        // Update permission counter
        var totalChecked = $('input[name="permissions[]"]:checked').length;
        var totalPermissions = $('input[name="permissions[]"]').length;
        $('#permissionCounter').text(totalChecked + ' dari ' + totalPermissions + ' permission dipilih');

        // Update main select all buttons
        if (totalChecked === 0) {
            $('#selectAllPermissions').removeClass('btn-outline-success').addClass('btn-success');
            $('#deselectAllPermissions').removeClass('btn-warning').addClass('btn-outline-warning');
        } else if (totalChecked === totalPermissions) {
            $('#selectAllPermissions').removeClass('btn-success').addClass('btn-outline-success');
            $('#deselectAllPermissions').removeClass('btn-outline-warning').addClass('btn-warning');
        } else {
            $('#selectAllPermissions').removeClass('btn-success').addClass('btn-outline-success');
            $('#deselectAllPermissions').removeClass('btn-warning').addClass('btn-outline-warning');
        }
    }

    // Update button states when individual checkboxes change
    $('input[name="permissions[]"]').change(function () {
        console.log('Checkbox changed');
        updateGroupButtonStates();
    });

    // Initial button state update
    updateGroupButtonStates();
});
