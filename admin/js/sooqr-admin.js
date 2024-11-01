(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

     // document.getElementsByClassName("switch").addEventListener("click", function(){
     //     if (!this.hasClass("focused")) {
     //         this.addClass("focused");
     //     }
     // });



     document.addEventListener('DOMContentLoaded', function(){
         if(window.location.href.includes('tab=xml_feed_options')) {

            var selectAllButton = '<th scope="row">Select all</th><td><label class="switch"><input type="checkbox" id="sooqr_xml_sqr_select_all" value="1" checked="checked" /><span class="slider rounded"></span></label></td>';
            var xmlTableClass = document.getElementsByClassName("form-table");
            var xmlTable = xmlTableClass[0];
            var tableBodyClass = xmlTable.children;
            var tableBody = tableBodyClass[0];
            HTMLElement.prototype.prependHtml = function (element) {
                const div = document.createElement('tr');
                div.innerHTML = element;
                this.insertBefore(div, this.firstChild);
            };
            HTMLElement.prototype.appendHtml = function (element) {
                const div = document.createElement('tr');
                div.innerHTML = element;
                this.appendChild(div);
            };
            // tableBody.prependHtml('<div class="selectAllBorderDiv"></div>');
            // tableBody.prependHtml(selectAllButton);
            // tableBody.firstChild.classList.add("xml-select-all");
            tableBody.appendHtml('<div class="selectAllBorderDiv"></div>');
            tableBody.appendHtml(selectAllButton);
            tableBody.lastChild.classList.add("xml-select-all");


            document.getElementById("sooqr_xml_sqr_select_all").addEventListener("click", function() {
                var inputs = xmlTable.querySelectorAll("input");
                if (this.checked) {
                    for (var i = 0; i < inputs.length; i++) {
                        if (!inputs[i].checked) {
                            inputs[i].checked = true;
                            if (!inputs[i].classList.contains("recheck")) {
                                inputs[i].classList.add("recheck");
                            }
                        }
                    }
                }
                if (!this.checked) {
                    var rechecks = document.getElementsByClassName("recheck");
                    if (!rechecks.length) {
                        for (var i = 0; i < inputs.length; i++) {
                            inputs[i].checked = false;
                            inputs[i].classList.add("recheck");
                        }
                    }
                    for (var i = 0; i < rechecks.length; i++){
                        rechecks[i].checked = false;
                    }
                }
            });

            var basicSwitches = document.getElementsByClassName("switchbasic");
            for (var i = 0; i < basicSwitches.length; i++) {

                basicSwitches[i].addEventListener("click", function() {

                    if (this.checked = true) {
                        if (this.classList.contains("recheck")) {
                            this.classList.remove("recheck");
                        }
                        if (document.getElementById("sooqr_xml_sqr_select_all").checked = true) {
                            var inputs = xmlTable.querySelectorAll("input");
                            for (var i = 0; i < inputs.length; i++) {
                                if (inputs[i].classList.contains("recheck")) {
                                    inputs[i].classList.remove("recheck");
                                }
                            }

                            // Check of alle checkboxes gecheckt zijn.
                            var checkedBoxes = document.getElementsByClassName("form-table")[0].querySelectorAll('input[type="checkbox"]:checked').length;
                            var CheckedAll = !(inputs.length - checkedBoxes);
                            if (CheckedAll) {
                                document.getElementById("sooqr_xml_sqr_select_all").checked = true;
                                this.getElementsByTagName("input")[0].classList.add("recheck");
                            }
                            if (!CheckedAll){
                                document.getElementById("sooqr_xml_sqr_select_all").checked = false;
                            }
                        }
                    }
                });
            };

        }

        if(document.getElementsByClassName("sooqrPlugin")[0].getElementsByClassName("button").length) {
            var buttonList = document.getElementsByClassName("sooqrPlugin")[0].getElementsByClassName("button");
            var loader = '<div class="spinner-container"><img class="sqr-spinner" src="../wp-content/plugins/sooqr-site-search/admin/images/sooqr_logo_spinner.png" alt="It\'s not working!"></div>';
            HTMLElement.prototype.appendHtml = function (element) {
                const span = document.createElement('span');
                span.innerHTML = element;
                this.appendChild(span);
            };
            for (var i = 0; i < buttonList.length; i++) {
                if(buttonList[i].classList.contains('delete') == false) {
                    buttonList[i].addEventListener("click", function(){
                        this.parentElement.appendHtml(loader);
                    });
                }
            }

            if(document.querySelectorAll("a#resetButton.button.delete").length) {
                window.confirmReset = function () {
                    var retVal = confirm("This will remove your current snippet. After resetting, you will have to save the general settings. Are you sure you want to reset the snippet?");
                    if( retVal == true ) {
                        var input = document.createElement("input");
                        input.setAttribute("type", "hidden");
                        input.setAttribute("name", "reset_javascript");
                        input.setAttribute("value", "true");
                        document.getElementById("editorFormDiv").appendChild(input);
                        document.querySelector("input#submit.button.button-primary").click();
                        return true;
                    }
                    else {
                        return false;
                    }
                }

                /*
                var editorSubHeader = '';
                document.querySelector('#editorFormDiv h2').appendHtml(editorSubHeader);



                document.querySelector("input#submit.button.delete").parentElement.style.display = 'none';
                *
                 */
            }
        }

    });



})( jQuery );
