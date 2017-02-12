$(function() {
    function checkChoices(qid) {
        var values = '';
        $('[data-qid=' + qid + ']').each(function(index){
            var val = parseInt($(this).val());
            if(val && val != NaN && val > 0) {
                values += val;
            }
        });

        $('[name="answers[' + qid + '][hidden]"]').val(values);
    }

    $('.order-input').each(function(){
        var qid = $(this).data('qid');
        checkChoices(qid);
    });

	$('.order-input').change(function(){
        var qid = $(this).data('qid');
        checkChoices(qid);
    });
});