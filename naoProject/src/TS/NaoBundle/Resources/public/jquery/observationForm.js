function getImageLoaderContainers() {
    return $('div#eo_eticketbundle_booking_tickets').find('.ticket-container');
}

//Build a ticket form
function ticketFormBuilder() {

    var $ticketsDiv   = $('div#eo_eticketbundle_booking_tickets');
    var $ticketContainers = getTicketsContainers();
    var $selectedDate = $('input.date-picker').val();
    var $ticketinfos  = {nbaddedticket: $ticketContainers.length, date: $selectedDate};
    $.post("add", $ticketinfos)
        .done( function(data) {

                var $index = getIndex();
                var $ticketForm = replaceBy(data.ticket,'__name__', $index);
                $ticketsDiv.append($ticketForm);

                var $allTicketContainers = getTicketsContainers();

                if($index === 0) {
                    var $firstTicket = $allTicketContainers.first();
                    autoFillTicketForm($firstTicket);
                    updatePrice($firstTicket.attr('id'));
                }

                var $newTicket = $ticketsDiv.find(':last-child');
                handlingBtns($newTicket);
                bindInputs($newTicket);

                var $disabled = (data.place <= 0);
                $('.addBtn').attr('disabled', $disabled);

                if($allTicketContainers.length > 0) {
                    var $step2 = $('.step-tickets');
                    var $step2BtnDiv = $step2.find('.step-buttons-container');
                    var $step2NextBtn = $step2BtnDiv.find('.nextBtn');
                    showStep($step2NextBtn);
                }
            }
        );
}
