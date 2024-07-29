const textarea = $('#long-url');
const maxHeight = 700; // Maximum height in pixels
const internalYOffset = 38;

$(document).ready(function() {
    // Setup form submission via ajax
    setupFormHandler();

    // Call autoResize on input event
    textarea.on('input', autoResize);

    // Initial call to set correct height
    autoResize();

    // Setup date picker
    setupDatepicker();

    // Setup clear date icon
    setupClearDateIcon();
});

function setupFormHandler() {
    $('#create-short-url-form').on('submit', function(e) {
        // Prevent the default form submission
        e.preventDefault();

        // Disable form elements
        setFormDisabled();

        // Clear any previous results
        resetPreviousResults();

        // Submit the form via ajax
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                // Re-enable the form
                setFormEnabled();

                // Show the result
                showShortUrlResult(response);
            },
            error: function(xhr, status, error) {
                // Re-enable the form
                setFormEnabled();

                console.error('AJAX Error:', status, error);
            }
        });
    });
}

function setFormDisabled() {
    $('#long-url').attr('readonly', true);
    $('#create-short-url-button').addClass('hidden');
    $('#create-short-url-processing-button').removeClass('hidden');
}

function setFormEnabled() {
    $('#long-url').attr('readonly', false);
    $('#create-short-url-processing-button').addClass('hidden');
    $('#create-short-url-button').removeClass('hidden');
}

function autoResize() {
    textarea.css('height', 'auto');
    const newHeight = Math.min(textarea[0].scrollHeight + internalYOffset, maxHeight);
    textarea.css('height', newHeight + 'px');

    // Show/hide scrollbar based on content height
    textarea.css('overflowY', textarea[0].scrollHeight + internalYOffset > maxHeight ? 'auto' : 'hidden');
}

function setupDatepicker() {
    const datePickerButton = document.getElementById('date-picker-button');

    flatpickr(datePickerButton, {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        time_24hr: true,
        minuteIncrement: 1,
        defaultDate: new Date().setHours(23, 59, 0, 0),
        onChange: function(selectedDates, dateStr) {
            $('#expiry-date').val(dateStr);
            $('#selected-date').html('<strong>Expiry:</strong> ' + formatDateTime(dateStr));
            $('#clear-date-icon').removeClass('hidden');
        }
    });
}

function formatDateTime(dateTimeStr) {
    const dateTime = new Date(dateTimeStr);
    const day = dateTime.getDate();
    const month = dateTime.toLocaleString('default', { month: 'long' });
    const year = dateTime.getFullYear();
    const time = dateTime.toLocaleString('default', { hour: '2-digit', minute: '2-digit', hour12: false });

    const suffix = getOrdinalSuffix(day);

    return `${day}${suffix} ${month} ${year} at ${time}`;
}

function getOrdinalSuffix(day) {
    if (day > 3 && day < 21) return 'th';
    switch (day % 10) {
        case 1:  return "st";
        case 2:  return "nd";
        case 3:  return "rd";
        default: return "th";
    }
}

function setupClearDateIcon() {
    $('#clear-date-icon').on('click', function() {
        clearDateSelection();
    });
}

function clearDateSelection() {
    $('#expiry-date').val('');
    $('#selected-date').html('');
    $('#clear-date-icon').addClass('hidden');
}

function showShortUrlResult(result) {
    $('#short-url-success-title').html(result.message);
    $('#short-url-container').html('<a href="'+result.short_url+'" target="_blank">'+result.short_url+'</a>');
    $('#short-url-result').removeClass('hidden')
        .removeClass('-translate-y-4')
        .addClass('translate-y-0')
        .delay(50)  // Small delay to ensure the transition happens
        .queue(function(next) {
            $(this).removeClass('opacity-0').addClass('opacity-100');
            next();
        });

    resetLongUrlForm();
}

function resetLongUrlForm() {
    $('#long-url').val('');
    clearDateSelection();
}

function resetPreviousResults() {
    $('#short-url-result').addClass('hidden')
        .removeClass('-translate-y-0')
        .addClass('translate-y-4')
        .removeClass('opacity-100')
        .addClass('opacity-0');
}
