var FNISIVChecker = {
    firstSectionElements: $('input[name=firstElement]'),
    secondSectionElements:$('input[name=secondElement]'),
    thirdSectionElements:$('input[name=thirdElement]'),
    switcherElements:$('.ch'),
    submitButtons:$('btn[name=submitBtn]'),
    selectedType:'SIV',
    enteredValue:'',
    hasErrors:false,
    init: function(){
        this.clearText();
        this.bindEvents();
        this.switcherElements.trigger('change');
    },
    bindEvents: function(){
        this.submitButtons.on('click',$.proxy(this.submitHandler,this));
        this.switcherElements.on('change',$.proxy(this.switcherHandler,this));
        this.firstSectionElements.on('input keyup change',$.proxy(this.changeSectionHandler,this));
        this.secondSectionElements.on('input keyup change',$.proxy(this.changeSectionHandler,this));
        this.thirdSectionElements.on('input keyup change',$.proxy(this.changeSectionHandler,this));
    },
    disableOther:function(current){
      switch (current){
          case 'first':
              this.secondSectionElements.attr('disabled','disabled');
              this.thirdSectionElements.attr('disabled','disabled');
              this.firstSectionElements.addClass('input-error');
              break;
          case 'second':
              this.firstSectionElements.attr('disabled','disabled');
              this.thirdSectionElements.attr('disabled','disabled');
              this.secondSectionElements.addClass('input-error');
              break;
          case 'third':
              this.firstSectionElements.attr('disabled','disabled');
              this.secondSectionElements.attr('disabled','disabled');
              this.thirdSectionElements.addClass('input-error');
              break;
      }
    },
    enableOther:function(current){
        switch (current){
            case 'first':
                this.secondSectionElements.removeAttr('disabled');
                this.thirdSectionElements.removeAttr('disabled');
                this.firstSectionElements.removeClass('input-error');
                break;
            case 'second':
                this.firstSectionElements.removeAttr('disabled');
                this.thirdSectionElements.removeAttr('disabled');
                this.secondSectionElements.removeClass('input-error');
                break;
            case 'third':
                this.firstSectionElements.removeAttr('disabled');
                this.secondSectionElements.removeAttr('disabled');
                this.thirdSectionElements.removeClass('input-error');
                break;
        }

    },
    enableAll:function(){
        this.firstSectionElements.removeAttr('disabled');
        this.secondSectionElements.removeAttr('disabled');
        this.thirdSectionElements.removeAttr('disabled');
        this.firstSectionElements.removeClass('input-error');
        this.secondSectionElements.removeClass('input-error');
        this.thirdSectionElements.removeClass('input-error');
    },
    messageBox:function(messageText, messageTitle, isError, resultOK){
        $('.modal-title').html(messageTitle);
        $('.modal-message').html(messageText);
        if (isError) {
            $('.is_success').hide();
            $('.is_error').show();
        } else {
            $('.is_success').show();
            $('.is_error').hide();
        }
        var messagedlg = $("#msgDlg").modal('show');
    },
    clearText:function(){
        this.firstSectionElements.val('');
        this.secondSectionElements.val('');
        this.thirdSectionElements.val('');

    },
    submitHandler:function(){
        if (!this.hasErrors && this.firstSectionElements.val() && this.secondSectionElements.val() && this.thirdSectionElements.val()) {
            document.location.href = '/app/#/FindInfo?plate=' + this.enteredValue;
        } else {
            this.messageBox("Vérifiez la saisie de votre "+this.selectedType +".", "FORMAT INCORRECT", true);
        }
    },
    changeSectionHandler:function(event){
        var self = this;
        var curElement = event.target;
        $(curElement).val($(curElement).val().toUpperCase());
        this[$(curElement).data('name')+'SectionElements'].val($(curElement).val());
        var checkResult = false;
        switch (self.selectedType){
            case 'SIV':
                var checkResult = false;
                switch($(curElement).data('name')){
                    case 'first':
                        //All charactesr exclude O,U,I and length should be 2
                        checkResult = /^([A-HJ-NP-TV-Z]){2,2}$/.test($(curElement).val());
                        break;
                    case 'second':
                        //All figures and length shoul be 3
                        checkResult = /^\d{3,3}$/.test($(curElement).val());
                        break;
                    case 'third':
                        //All charactesr exclude O,U,I and length should be 2
                        checkResult = /^([A-HJ-NP-TV-Z]){2,2}$/.test($(curElement).val());
                        break;
                }
                break;
            case 'FNI':
                switch($(curElement).data('name')){
                    case 'first':
                        //All figures and length shoul be from 1 to 4
                        checkResult = /^\d{1,4}$/.test($(curElement).val());
                        break;
                    case 'second':
                        //All charactesr exclude O,U,I and length should be from 2 to 3
                        checkResult = /^([A-HJ-NP-TV-Z]){2,3}$/.test($(curElement).val());
                        break;
                    case 'third':
                        if($(curElement).val().length<3){
                            checkResult = /^([0-8][1-9]|[1-9][0-5]|2[A-B])$/.test($(curElement).val());//01-95 and 2A 2B check
                        } else {
                            checkResult = /^(69[M]|97[1-4]|97[6])$/.test($(curElement).val());//69M, 971, 972, 973, 974, 976 check
                        }
                        break;
                }
                break;
        }
        if(checkResult){
            this.enableOther($(curElement).data('name'));
            this.hasErrors = false;
            this.enteredValue = this.firstSectionElements.val()+'-'+this.secondSectionElements.val()+'-'+this.thirdSectionElements.val();
            return true;
        } else {
            this.disableOther($(curElement).data('name'));
            this.hasErrors = true;
            return false;
        }
    },
    switcherHandler: function (event) {
        var self = this;
        var currElement = event.target;
        $('.row-box > div').removeClass('show');
        $('.ch').each(function(){
            $(this).parent().parent().prev().find('>div').removeClass('show');
            if($(currElement).is(':checked')) {
                self.selectedType = 'FNI';
                $(this).prop('checked','checked');
                $(this).parent().parent().prev().find('.vin-nouvelle').addClass('show');
            }else {
                self.selectedType = 'SIV';
                $(this).prop('checked','');
                $(this).parent().parent().prev().find('.vin-ancienne').addClass('show');
            }
        });
        self.clearText();
        self.enableAll();
    }
};


$(document).ready(function () {
    FNISIVChecker.init();
});
