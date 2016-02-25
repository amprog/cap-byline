/**
 * Defines JavaScript code for CAP_Byline
 * 
 * Copyright (C) 2013 - 2016  The Center for American Progress
 *
 * CAP_Byline is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CAP_Byline is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CAP_Byline.  If not, see <http://www.gnu.org/licenses/gpl.html>.
 **/

jQuery(document).ready(function() {
                
  jQuery("#contact-modal-link").click(function() {
    jQuery("#contact-modal").addClass("active");
  });  // end of contact-modal-link.click
  
  jQuery("#contact-modal .close-modal").click(function() {
    jQuery("#contact-modal").removeClass("active");
  }); // end of contact-modal-close-modal.click
  
});  // End of document.ready