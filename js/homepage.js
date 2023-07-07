    // JavaScript code to fetch and display menu item details
        $(document).ready(function() {
            $(".view-details").click(function() {
                var menuItemId = $(this).data("menuitemid");
                var detailsContainer = $("#menu-details-" + menuItemId);
                if (detailsContainer.is(":empty")) {
                    $.ajax({
                        url: "get-menu-details.php",
                        type: "POST",
                        data: { menuItemId: menuItemId },
                        success: function(response) {
                            detailsContainer.html(response);
                        }
                    });
                }
                detailsContainer.toggle();
            });
        });
