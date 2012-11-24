var K2Calendar = function(container, baseUrl){    
    this.container = $(container);
    this.calendar = this.container.find('.k2_calendar');
    this.baseUrl = baseUrl;
    
    var instance = this; 
    
    Date.prototype.toString = function(){
        return $.fullCalendar.formatDate(this, 'yyyy-MM-dd HH:mm:ss');
    }

    this.calendar.fullCalendar({
        monthNames:["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
        monthNamesShort:["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        dayNames:["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"],
        dayNamesShort:["Dom","Lun","Mar","Mie","Jue","Vie","Sab"],
        header:{
            right:  'month basicWeek agendaWeek prev,next'
        },
        events: function(start, end, callback){
            $.get(instance.baseUrl + 'event',{
                start: start,
                end: end
            }, callback, 'json')
        },
        selectable: true,
        editable: true,
        select:function( startDate, endDate, allDay){
            instance.showEvent(null, startDate, endDate);
        },
        eventClick:function(event){
            instance.showEvent(event)
        },
        eventResize:function(event, dayDelta){
            var data = {};
            for(attr in event){
                if(!$.isPlainObject(event[attr])){
                    data[attr] = event[attr]                        
                }
            }
            instance.saveEvent(event, {
                'event': data
            })
        },
        eventDrop:function(event, dayDelta){
            var data = {};
            for(attr in event){
                if(!$.isPlainObject(event[attr])){
                    data[attr] = event[attr]                        
                }
            }
            instance.saveEvent(event, {
                'event': data
            })
        }
    }) 
    
    this.showEvent = function(event, startDate, endDate){
        var id = (null != event) ? event.id : '';
        var dialog = $('<div/>').load(instance.baseUrl + 'event/form/' + id,function(){
            dialog.find('form:first').on('submit',function(event){
                event.preventDefault()
                dialog.dialog('option','buttons').Guardar()
            })
            dialog.dialog({
                title:'Registro de Evento',
                width: 500,
                modal: true,
                buttons:{
                    Guardar:function(){
                        var data = dialog.find('form:first').serialize()
                        instance.saveEvent(event, data, dialog, function(){
                            dialog.dialog('close')                            
                        })
                    },
                    Cancelar:function(){
                        dialog.dialog('close')
                    },
                    Remover:function(){
                        instance.deleteEvent(dialog.find('form:first #event_id').val(), dialog, function(){
                            dialog.dialog('close')                            
                        })
                    }
                },
                close:function(){
                    dialog.find('form:first').off()
                    dialog.remove()
                }
            })
            if (undefined != startDate && undefined != endDate){
                dialog.find('form:first #event_start').val(startDate)
                dialog.find('form:first #event_end').val(endDate)                
            }
        })
    }
    
    this.saveEvent = function(event, data, dialog, callbackSuccess)
    {
        $.post(instance.baseUrl + 'event/save', data, function(json){
            if(null == event){ 
                var action = 'renderEvent'
                event = json
            }else{
                var action = 'updateEvent' 
                for(attr in json){
                    event[attr] = json[attr]   
                }
            }
            instance.calendar.fullCalendar(action, event)
            if ($.isFunction(callbackSuccess)){
                callbackSuccess()
            }
        }, 'json').error(function(error){
            if(null != dialog && undefined != dialog){
                instance.showErrors(dialog, error.responseText)
            }else{
                instance.showErrors(instance.container, error.responseText)
            }
        })
    }
    
    this.deleteEvent = function(id, dialog, callbackSuccess){
        if(0 != id.length){            
            $.getJSON(instance.baseUrl + 'event/remove/' + id,{}, function(json){
                instance.calendar.fullCalendar('removeEvents', id)
                if ($.isFunction(callbackSuccess)){
                    callbackSuccess()
                }
            }, 'json').error(function(error){
                if(null != dialog && undefined != dialog){
                    instance.showErrors(dialog, error.responseText)
                }else{
                    instance.showErrors(instance.container, error.responseText)
                }
                
            })
        }else{
            if ($.isFunction(callbackSuccess)){
                callbackSuccess()
            }        
        }
    }
    
    this.showErrors = function(container, error)
    {
        var error = $.parseJSON(error)
        var errors = [];
        errors.push(error.message)
        if(undefined != error.errors){           
            for (e in error.errors){
                errors.push(error.errors[e])
            }
        }
        container.find('.k2_calendar_errors').html('<p>' + errors.join('</p><p>') + '</p>').show(0).delay(5000).hide(0)
    }

}
