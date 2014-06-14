/**
 * Core Application
 *
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 *
 * For DocBlock reference
 *
 * @see http://usejsdoc.org/
 */
var app = function() {
	var obj = {
		components: {},
		el: {
			confirm: $('.confirm'),
			confirmModal: $('#confirm-modal')
		},
		/**
		 * Initialize
		 * 
		 * @returns {Void}
		 */
		initialize: function() {
			this.events();
		},
		/**
		 * Setup common events
		 * 
		 * @returns {Void}
		 */
		events: function() {

			// Confirm modal
			this.el.confirm.on('click', function(e) {
				e.preventDefault();

				obj.methods.confirm($(this).attr('href'), $(this).data('confirm'));
			});

		},
		methods: {
			/**
			 * Launch a confirmation modal before proceeding to the page
			 *
			 * @param {String} href The url to navigate to on success
			 * @param {String} msg Confirmation message
			 * @return {Void}
			 */
			confirm: function(href, msg) {
				var display = obj.el.confirmModal;

				// Set options
				$('.yes', display).attr('href', href);
				$('.confirm-message', display).text((msg !== undefined ? msg : 'Are you sure?'));

				// Display the modal
				display.modal('show');
			}
		}
	};

	obj.initialize();

	// Return our public methods
	return obj.methods;
}();