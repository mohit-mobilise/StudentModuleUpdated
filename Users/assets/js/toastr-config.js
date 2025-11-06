/**
 * Toastr Configuration
 * This file configures toastr for all pages
 * Include this file after toastr.min.js on all pages that use toastr
 */

// Configure toastr to display for 3 seconds
if (typeof toastr !== 'undefined') {
    toastr.options = {
        timeOut: 3000,
        extendedTimeOut: 1000,
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut',
        tapToDismiss: false,
        preventDuplicates: false,
        newestOnTop: true
    };
}

