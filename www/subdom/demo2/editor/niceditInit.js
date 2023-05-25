$(function() {
	var editor = {
		init: function($context) {
			$.each($context, function(){
				var $editors = $(this).find('textarea.editor');
				$.each($editors, function(){
					var name = $(this).css('width','100%').attr('name');
					bkLib.onDomLoaded(function() {
						new nicEditor({
							maxHeight: 180,
							buttonList: ["bold","italic","underline","subscript","superscript","strikethrough","left","center","right","justify","ol","ul","indent" ,"outdent" ,"hr","fontSize","fontFormat","templates","special","image","removeformat","image","createLink","deleteLink","fontColor","backgroundColor","htmlSource"]						
						})
						.panelInstance("editor_"+name); 
					});
				});
			});
			setTimeout(function(){
				editor.onPaste();
			},150);
		},
		onPaste: function(){
			var $initialized = $('body').find('.nicEdit-main');
			$.each($initialized, function(){
				$(this).on('paste', function(e){
					var $focused = $(document.activeElement);
					var str = "";
					setTimeout(function(){
						var input = $focused.html();
						var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g; 
						var output = input.replace(stringStripper, ' ');					
						var commentSripper = new RegExp('<!--(.*?)-->','g');
						var output = output.replace(commentSripper, '');
						var tagStripper = new RegExp('<(/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>','gi');						
						output = output.replace(tagStripper, '');						
						var badTags = ['style', 'script','applet','embed','noframes','noscript'];
						for (var i=0; i< badTags.length; i++) {
							tagStripper = new RegExp('<'+badTags[i]+'.*?'+badTags[i]+'(.*?)>', 'gi');
							output = output.replace(tagStripper, '');
						}
						var badAttributes = ['style', 'start'];
						for (var i=0; i< badAttributes.length; i++) {
							var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"','gi');
							output = output.replace(attributeStripper, '');
						}		
						$focused.html(output);
					},200);
				});
			});
		}
	};
	$(document).ready(function() {
			editor.init($(document));
	});
});
var nicEditorConfig = bkClass.extend({
	buttons : {
		'bold' : {name : __('Tučně'), command : 'Bold', tags : ['B','STRONG'], css : {'font-weight' : 'bold'}, key : 'b'},
		'italic' : {name : __('Kurzíva'), command : 'Italic', tags : ['EM','I'], css : {'font-style' : 'italic'}, key : 'i'},
		'underline' : {name : __('Podtrženo'), command : 'Underline', tags : ['U'], css : {'text-decoration' : 'underline'}, key : 'u'},
		'left' : {name : __('Zarovnání vlevo'), type : 'nicAlignLeftButton', noActive : true},
		'center' : {name : __('Zarovnání na střed'), type : 'nicAlignCenterButton', noActive : true},
		'right' : {name : __('Zarovnání vpravo'), type : 'nicAlignRightButton', noActive : true},
		'justify' : {name : __('Zarovnání do bloku'), type : 'nicAlignJustifyButton', noActive : true},
		'ol' : {name : __('Vložit číselný seznam'), command : 'insertorderedlist', tags : ['OL']},
		'ul' : 	{name : __('Vložit odrážkový seznam'), command : 'insertunorderedlist', tags : ['UL']},
		'subscript' : {name : __('Horní index'), command : 'subscript', tags : ['SUB']},
		'superscript' : {name : __('Dolní index'), command : 'superscript', tags : ['SUP']},
		'strikethrough' : {name : __('Přeškrtnuto'), command : 'strikeThrough', css : {'text-decoration' : 'line-through'}},
		'removeformat' : {name : __('Odstranit formátování'), command : 'removeformat', noActive : true},
		'indent' : {name : __('Odsadit text'), command : 'indent', noActive : true},
		'outdent' : {name : __('Zrušit odsazení'), command : 'outdent', noActive : true},
		'hr' : {name : __('Dělící čára'), command : 'insertHorizontalRule', noActive : true},
		'createLink' : {name : 'Přidání / editace odkazu', type : 'nicLinkButton', tags : ['A']},
		'deleteLink' : {name : 'Smazání odkazu',  type : 'nicUnlinkButton', noActive : true},
		'fontColor' : {name : __('Barva textu'), type : 'nicEditorColorButton', noClose : true},
		'backgroundColor' : {name : __('Barva pozadí'), type : 'nicEditorBgColorButton', noClose : true},
		'htmlSource' : {name : 'Editace HTML', type : 'nicCodeButton'}
	},
	iconsPath : '/editor/niceditoricons.png', 
	buttonList : ['save','bold','italic','underline','left','center','right','justify','ol','ul','fontSize','fontFamily','fontFormat','template','indent','outdent','createLink','unlink','fontColor','backgroundColor'],		
	iconList : {"htmlSource":1,"backgroundColor":2,"fontColor":3,"bold":4,"center":5,"hr":6,"indent":7,"italic":8,"justify":9,"left":10,"ol":11,"outdent":12,"removeformat":13,"right":14,"save":25,"strikethrough":16,"subscript":17,"superscript":18,"ul":19,"underline":20,"createLink":22,"deleteLink":23,"close":24,"arrow":26,"templates":28,"special":29,"image":30}
});	
var nicSelectOptions = bkClass.extend({
	buttons : {
		'fontSize' : {name : __('Zvolte velikost písma'), type : 'nicEditorFontSizeSelect', command : 'fontsize'},
		'fontFamily' : {name : __('Zvolte řez písma'), type : 'nicEditorFontFamilySelect', command : 'fontname'},
		'fontFormat' : {name : __('Zvolte formátování písma'), type : 'nicEditorFontFormatSelect', command : 'formatBlock'}
	}
});	
var nicEditorSelect = bkClass.extend({
    construct: function(D, A, C, B) {
        this.options = C.buttons[A];
        this.elm = D;
        this.ne = B;
        this.name = A;
        this.selOptions = new Array();
        this.margin = new bkElement("div").setStyle({
            "float": "left",
            margin: "2px 1px 0 1px"
        }).appendTo(this.elm);
        this.contain = new bkElement("div").setStyle({
            width: "130px",
            height: "20px",
            cursor: "pointer",
            overflow: "hidden"
        }).addClass("selectContain").addEvent("click", this.toggle.closure(this)).appendTo(this.margin);
        this.items = new bkElement("div").setStyle({
            overflow: "hidden",
            zoom: 1,
            border: "1px solid #ccc",
            paddingLeft: "3px",
            backgroundColor: "#fff"
        }).appendTo(this.contain);
        this.control = new bkElement("div").setStyle({
            overflow: "hidden",
            "float": "right",
            height: "18px",
            width: "16px"
        }).addClass("selectControl").setStyle(this.ne.getIcon("arrow", C)).appendTo(this.items);
        this.txt = new bkElement("div").setStyle({
            overflow: "hidden",
            "float": "left",
            width: "105px",
            height: "14px",
            marginTop: "2px",
            fontFamily: "sans-serif",
            textAlign: "left",
            fontSize: "12px"
        }).addClass("selectTxt").appendTo(this.items);
        if (!window.opera) {
            this.contain.onmousedown = this.control.onmousedown = this.txt.onmousedown = bkLib.cancelEvent
        }
        this.margin.noSelect();
        this.ne.addEvent("selected", this.enable.closure(this)).addEvent("blur", this.disable.closure(this));
        this.disable();
        this.init()
    },
    disable: function() {
        this.isDisabled = true;
        this.close();
        this.contain.setStyle({
            opacity: 0.6
        })
    },
    enable: function(A) {
        this.isDisabled = false;
        this.close();
        this.contain.setStyle({
            opacity: 1
        })
    },
    setDisplay: function(A) {
        this.txt.setContent(A)
    },
    toggle: function() {
        if (!this.isDisabled) {
            (this.pane) ? this.close(): this.open()
        }
    },
    open: function() {
        this.pane = new nicEditorPane(this.items, this.ne, {
            width: "129px",
            padding: "0px",
            borderTop: 0,
            borderLeft: "1px solid #ccc",
            borderRight: "1px solid #ccc",
            borderBottom: "0px",
            backgroundColor: "#fff"
        });
        for (var C = 0; C < this.selOptions.length; C++) {
            var B = this.selOptions[C];
            var A = new bkElement("div").setStyle({
                overflow: "hidden",
                borderBottom: "1px solid #ccc",
                width: "129px",
                textAlign: "left",
                overflow: "hidden",
                cursor: "pointer"
            });
            var D = new bkElement("div").setStyle({
                padding: "0px 4px"
            }).setContent(B[1]).appendTo(A).noSelect();
            D.addEvent("click", this.update.closure(this, B[0])).addEvent("mouseover", this.over.closure(this, D)).addEvent("mouseout", this.out.closure(this, D)).setAttributes("id", B[0]);
            this.pane.append(A);
            if (!window.opera) {
                D.onmousedown = bkLib.cancelEvent
            }
        }
    },
    close: function() {
        if (this.pane) {
            this.pane = this.pane.remove()
        }
    },
    over: function(A) {
        A.setStyle({
            backgroundColor: "#ccc"
        })
    },
    out: function(A) {
        A.setStyle({
            backgroundColor: "#fff"
        })
    },
    add: function(B, A) {
        this.selOptions.push(new Array(B, A))
    },
    update: function(A) {
        this.ne.nicCommand(this.options.command, A);
        this.close()
    }
});
var nicEditorFontSizeSelect = nicEditorSelect.extend({
    sel: {
        "10px": "10 px",
        "12px": "12 px",
        "14px": "14 px",
        "16px": "16 px",
        "18px": "18 px",
        "20px": "20 px",
        "24px": "24 px",
        "28px": "28 px",
        "32px": "32 px",
        "36px": "36 px",
        "42px": "42 px"
    },
    init: function() {
        this.setDisplay("Velikost písma");
        for (itm in this.sel) {
            this.add(itm, '<span style="font-size:' + itm + '">' + this.sel[itm] + "</span>")
        }
    },
		update : function(elm) {
        var newNode = document.createElement('span');
        newNode.style.fontSize = elm;
        var rng = this.ne.selectedInstance.getRng().surroundContents(newNode);
        this.close();
    }
});
var nicEditorFontFormatSelect = nicEditorSelect.extend({
    sel: {
			p: "Odstavec",
			h1: "H1",
			h2: "H2",
			h3: "H3",
			h4: "H4",
			h5: "H5",
			h6: "H6",
    },
    init: function() {
        this.setDisplay("Formátování písma");
        for (itm in this.sel) {
            var A = itm.toUpperCase();
            this.add("<" + A + ">", "<" + itm + ' style="padding: 0px; margin: 0px;">' + this.sel[itm] + "</" + A + ">")
        }
    }
});
var nicLinkButton = nicEditorAdvancedButton.extend({	
	addPane : function() {
		this.removePane();
	},
	submit : function(e) {
		this.ne.selectedInstance.restoreRng();
		this.ln = this.ne.selectedInstance.selElm().parentTag('A');
		if(!this.ln) {
			var tmp = 'javascript:nicTemp();';
			this.ne.nicCommand("createlink",tmp);
			this.ln = this.findElm('A','href',tmp);
		}
		var $dialog = $('.linkDialog');
		var type = parseInt($dialog.find('.linkType').val());
		switch(type){
			case 0:
				if(this.ln) {
					var urlPattern = /^[w]{3}[.]{1}[a-zA-Z0-9][a-zA-Z0-9-_.]+[.][a-zA-Z]{2,}$/g;
					var href = $dialog.find('#src_'+type).val();
					if(urlPattern.test(href)){
						href = 'http://'+href;
					}
					this.ln.setAttributes({
						href : href,
						title : $dialog.find('#title_'+type).val(),
						target: '_'+$dialog.find('#target_'+type).val()
					});
				}
				break;
			case 1:
				if(this.ln) {
					$(this.ne.selectedInstance.selElm()).removeAttr('target title');
					this.ln.setAttributes({
						href : 'mailto:'+encodeURIComponent($dialog.find('#to_'+type).val())+'?subject='+encodeURIComponent($dialog.find('#subject_'+type).val())+'&body='+encodeURIComponent($dialog.find('#body_'+type).val())
					});
				}
				break;
			case 2:
				if(this.ln) {
					$(this.ne.selectedInstance.selElm()).removeAttr('target title');
					this.ln.setAttributes({
						href : 'tel:'+$dialog.find('#tel_'+type).val()
					});
				}
				break;
			case 3:
				if(this.ln) {
					$(this.ne.selectedInstance.selElm()).removeAttr('target title');
					var full = $dialog.find('#full_'+type).val();
					var preview = $dialog.find('#preview_'+type).val();
					var alt = $dialog.find('#name_'+type).val();
					var title = $dialog.find('#title_'+type).val();
					$(this.ln).addClass('lightbox').attr('title',title).attr('href',full).html('<img src="'+preview+'" alt="'+alt+'" />');
					if($('#gallery_'+type).prop('checked')){
						$(this.ln).attr('rel','gallery');
					}else{
						$(this.ln).removeAttr('rel');
					}
				}else{
					var full = $dialog.find('#full_'+type).val();
					var preview = $dialog.find('#preview_'+type).val();
					var alt = $dialog.find('#name_'+type).val();
					var title = $dialog.find('#title_'+type).val();
					if($('#gallery_'+type).prop('checked')){
						var html = '<a href="'+full+'" title="'+title+'" class="lightbox" rel="gallery"><img src="'+preview+'" alt="'+alt+'" /></a>';
					}else{
						var html = '<a href="'+full+'" title="'+title+'" class="lightbox"><img src="'+preview+'" alt="'+alt+'" /></a>';
					}
					this.ne.selectedInstance.nicCommand('insertHTML', html);
				}
				break;
			default:
				alert('Litujeme, ale nastala chyba. Prosím zkuste to znovu a v případě opakovaného neúspěchu zkustě obnovit stránku (F5)' + type);
		}
		$('.linkDialog').dialog("close");
		this.ne.selectedInstance.restoreRng();
	},
	createDialog: function($focused){
		var $this = this;
		var $dialog = $('.linkDialog');
		if($dialog.length < 1){
			$('body').append('<div class="linkDialog" />');
			var $dialog = $('.linkDialog');
			$dialog.dialog({
				width: 400,
				closeOnEscape: true,
				title: "Přídání / editace odkazu",
				resizable: false,
				autoOpen: false,
				buttons: [{
					text: "Uložit",
					click: function(e){
						$focused.focus();
						$this.submit();
					}
				},
				{
					text: "Zavřít",
					click: function(){
						$dialog.dialog('close');
					}
				}]
			});
			$dialog.append('<select class="linkType"></select>');
			var $linkTypeWrap = $dialog.find('select.linkType');
			var linkType = ['Odkaz na stránku / soubor','Odkaz na email','Odkaz na telefonní číslo','Galerie (lightbox)'];
			$.each(linkType, function(key, val){
				$linkTypeWrap.append('<option value="'+key+'">'+val+'</option>');
				$dialog.append('<div class="linkAttributes" />');
				switch(key){
					case 0:
						$dialog.find('.linkAttributes').last()
						.append('<label for="src_'+key+'">Odkaz:</label><input type="text" data-attr="src" id="src_'+key+'" /><button id="fm_0" class="browseFiles ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">Vybrat soubor</button><button id="fm_1" class="browseImages ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">Vybrat obrázek</button><label for="title_'+key+'">Popis:</label><input type="text" data-attr="title" id="title_'+key+'" /><label for="target_'+key+'">Zobrazit v:</label><select data-attr="target" id="target_'+key+'"><option value="self">Ve vlastním okně</option><option value="blank">V novém panelu</option></select>');
						fm.init('files', '#src_'+key,  '#fm_0');
						fm.init('images', '#src_'+key, '#fm_1');
						break;
					case 1:
						$dialog.find('.linkAttributes').last()
						.append('<label for="to_'+key+'">Komu:</label><input type="text" data-attr="to" id="to_'+key+'" /><label for="subject_'+key+'">Předmět:</label><input type="text" data-attr="subject" id="subject_'+key+'" /><label for="body_'+key+'">Zpráva:</label><textarea type="text" data-attr="body" id="body_'+key+'"></textarea>');
						break;
					case 2:
						$dialog.find('.linkAttributes').last()
						.append('<label for="tel_'+key+'">Telefoní číslo:</label><input type="text" data-attr="tel" id="tel_'+key+'" />');
						break;
					case 3:
						$dialog.find('.linkAttributes').last()
						.append('<label for="preview_'+key+'">Náhledový obrázek:</label><input type="text" data-attr="preview" id="preview_'+key+'" /><button id="fm_2" class="browseImages ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">Vybrat obrázek</button><label for="full_'+key+'">Zvětšený obrázek:</label><input type="text" data-attr="full" id="full_'+key+'" /><button id="fm_3" class="browseImages ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">Vybrat obrázek</button><label for="name_'+key+'">Název:</label><input type="text" data-attr="title" id="name_'+key+'" /><label for="title_'+key+'">Popis:</label><input type="text" data-attr="title" id="title_'+key+'" /><input type="checkbox" data-attr="gallery" id="gallery_'+key+'" checked="checked" /><label for="gallery_'+key+'">Vložit tento obrázek do galerie</label>');
						fm.init('images', '#preview_'+key, '#fm_2');
						fm.init('images', '#full_'+key, '#fm_3');
						break;
				}
			});
			var $linkTypeContent = $dialog.find('div.linkAttributes');
			$linkTypeContent.hide().first().show();
			$linkTypeWrap.change(function(){
				$linkTypeContent.hide().eq($(this).val()).show();
			});
		}
		this.ln = this.ne.selectedInstance.selElm().parentTag('A');
		if(this.ln){
			var $link = $(this.ne.selectedInstance.selElm().parentTag('A'));	
			if($link.length > 0){
				if($link.parent().hasClass('lightbox')){
						$dialog.find('.linkAttributes').hide().eq(3).show();
						$dialog.find('.linkType').val(3);
						$dialog.find('#preview_3').val($link.attr('src'));
						$dialog.find('#full_3').val($link.parent().attr('href'));
						$dialog.find('#name_3').val($link.attr('alt'));
						$dialog.find('#title_3').val($link.parent().attr('title'));
						if($link.parent().attr('rel') == 'gallery'){
							$dialog.find('#gallery_3').prop('checked', true);
						}else{
							$dialog.find('#gallery_3').prop('checked', false);
						}						
				}else{
					if($link.hasClass('lightbox')){
						$dialog.find('.linkAttributes').hide().eq(3).show();
						$dialog.find('.linkType').val(3);
						$dialog.find('#preview_3').val($link.children('img').attr('src'));
						$dialog.find('#full_3').val($link.attr('href'));
						$dialog.find('#name_3').val($link.children('img').attr('alt'));
						$dialog.find('#title_3').val($link.attr('title'));
						if($link.attr('rel') == 'gallery'){
							$dialog.find('#gallery_3').prop('checked', true);
						}else{
							$dialog.find('#gallery_3').prop('checked', false);
						}
					}else{
						if($link.attr('href').substring(0,7) == 'mailto:'){
							$dialog.find('.linkAttributes').hide().eq(1).show();
							var link = $link.attr('href').substring(7);
							var tmp = link.split('?subject=');
							var to = decodeURIComponent(tmp[0]);
							tmp = tmp[1].split('&body=');
							var subject = decodeURIComponent(tmp[0]);
							var body = decodeURIComponent(tmp[1]);
							$dialog.find('.linkType').val(1);
							$dialog.find('#to_1').val(to);
							$dialog.find('#subject_1').val(subject);
							$dialog.find('#body_1').val(body);
						}else if($link.attr('href').substring(0,4) == 'tel:'){
							$dialog.find('.linkAttributes').hide().eq(2).show();
							$dialog.find('.linkType').val(2);
							$dialog.find('#tel_2').val($link.attr('href').substring(4));
						}else{						
							$dialog.find('.linkAttributes').hide().eq(0).show();
							$dialog.find('.linkType').val(0);
							$dialog.find('#src_0').val($link.attr('href'));
							$dialog.find('#title_0').val($link.attr('title'));
							$dialog.find('#target_0').val($link.attr('target').substring(1));
						}
					}
				}
			}
		}
		$('.linkDialog').dialog('open');
	},
	mouseClick: function(){
		this.ne.selectedInstance.saveRng();
		var $focused = $(document.activeElement); 
		this.createDialog($focused); 
	}
});
var nicUnlinkButton = nicEditorAdvancedButton.extend({	
	mouseClick: function(){
		var $link = $(this.ne.selectedInstance.selElm());	
		if($link.parent().is('a')){
			$link.unwrap('a');
		}
	}
});
var nicAlignLeftButton = nicEditorAdvancedButton.extend({	
	displayStyle: function(el){
		var cStyle,
		t = document.createElement(el.tagName),
		gcs = "getComputedStyle" in window;
		document.body.appendChild(t);
		cStyle = (gcs ? window.getComputedStyle(t, "") : t.currentStyle).display; 
		document.body.removeChild(t);
		return cStyle;
	},
	mouseClick: function(){
		var el = this.ne.selectedInstance.selElm();
		var $el = $(el);
		var $p = $el.parent();
		var p = $p[0];
		if(this.displayStyle(p) == 'inline'){
			console.log('p inline');
			var $pp = $p.parent();
			var pp = $pp[0];
			if(this.displayStyle(pp) == 'inline'){
				console.log('pp inline');
				$el.wrap('<div>').parent().css('text-align','left');
			}else if($pp.hasClass('nicEdit-main') == true){
				console.log('pp nicEdit');
				$p.wrap('<div>').parent().css('text-align','left');
			}else{
				console.log('pp block');
				$p.css('text-align','left');
			}
		}else if($p.hasClass('nicEdit-main') == true){
			console.log('p nicEdit');
			$el.wrap('<div>').parent().css('text-align','left');
		}else{
			console.log('p block');
			$p.css('text-align','left');
		}
	}
});
var nicAlignRightButton = nicEditorAdvancedButton.extend({	
	displayStyle: function(el){
		var cStyle,
		t = document.createElement(el.tagName),
		gcs = "getComputedStyle" in window;
		document.body.appendChild(t);
		cStyle = (gcs ? window.getComputedStyle(t, "") : t.currentStyle).display; 
		document.body.removeChild(t);
		return cStyle;
	},
	mouseClick: function(){
		var el = this.ne.selectedInstance.selElm();
		var $el = $(el);
		var $p = $el.parent();
		var p = $p[0];
		if(this.displayStyle(p) == 'inline'){
			console.log('p inline');
			var $pp = $p.parent();
			var pp = $pp[0];
			if(this.displayStyle(pp) == 'inline'){
				console.log('pp inline');
				$el.wrap('<div>').parent().css('text-align','right');
			}else if($pp.hasClass('nicEdit-main') == true){
				console.log('pp nicEdit');
				$p.wrap('<div>').parent().css('text-align','right');
			}else{
				console.log('pp block');
				$p.css('text-align','right');
			}
		}else if($p.hasClass('nicEdit-main') == true){
			console.log('p nicEdit');
			$el.wrap('<div>').parent().css('text-align','right');
		}else{
			console.log('p block');
			$p.css('text-align','right');
		}		
	}
});
var nicAlignCenterButton = nicEditorAdvancedButton.extend({	
	displayStyle: function(el){
		var cStyle,
		t = document.createElement(el.tagName),
		gcs = "getComputedStyle" in window;
		document.body.appendChild(t);
		cStyle = (gcs ? window.getComputedStyle(t, "") : t.currentStyle).display; 
		document.body.removeChild(t);
		return cStyle;
	},
	mouseClick: function(){
		var el = this.ne.selectedInstance.selElm();
		var $el = $(el);
		var $p = $el.parent();
		var p = $p[0];
		if(this.displayStyle(p) == 'inline'){
			console.log('p inline');
			var $pp = $p.parent();
			var pp = $pp[0];
			if(this.displayStyle(pp) == 'inline'){
				console.log('pp inline');
				$el.wrap('<div>').parent().css('text-align','center');
			}else if($pp.hasClass('nicEdit-main') == true){
				console.log('pp nicEdit');
				$p.wrap('<div>').parent().css('text-align','center');
			}else{
				console.log('pp block');
				$p.css('text-align','center');
			}
		}else if($p.hasClass('nicEdit-main') == true){
			console.log('p nicEdit');
			$el.wrap('<div>').parent().css('text-align','center');
		}else{
			console.log('p block');
			$p.css('text-align','center');
		}
	}
});
var nicAlignJustifyButton = nicEditorAdvancedButton.extend({	
	displayStyle: function(el){
		var cStyle,
		t = document.createElement(el.tagName),
		gcs = "getComputedStyle" in window;
		document.body.appendChild(t);
		cStyle = (gcs ? window.getComputedStyle(t, "") : t.currentStyle).display; 
		document.body.removeChild(t);
		return cStyle;
	},
	mouseClick: function(){
		var el = this.ne.selectedInstance.selElm();
		var $el = $(el);
		var $p = $el.parent();
		var p = $p[0];
		if(this.displayStyle(p) == 'inline'){
			console.log('p inline');
			var $pp = $p.parent();
			var pp = $pp[0];
			if(this.displayStyle(pp) == 'inline'){
				console.log('pp inline');
				$el.wrap('<div>').parent().css('text-align','justify');
			}else if($pp.hasClass('nicEdit-main') == true){
				console.log('pp nicEdit');
				$p.wrap('<div>').parent().css('text-align','justify');
			}else{
				console.log('pp block');
				$p.css('text-align','justify');
			}
		}else if($p.hasClass('nicEdit-main') == true){
			console.log('p nicEdit');
			$el.wrap('<div>').parent().css('text-align','justify');
		}else{
			console.log('p block');
			$p.css('text-align','justify');
		}
	}
});
var fm = {
	init: function(type, key, btn) {
		if(type == 'images'){
			$(btn).click(function(){
				fm.open(type, key);
			});
		}else if(type == 'files'){
			$(btn).click(function(){
				fm.open(type, key);
			});
		}
	},
	open: function(type, key){
		$('body').append('<div class="fm"><div class="fm-status"><div class="fm-folder">File manager</div><a class="fa fa-close" title="Zavřít"></a></div><div class="fm-action"></div><div class="fm-tree"></div><div class="fm-content"></div></div>');
		var $fmw = $('.fm').css('top',$(window).scrollTop()+100);
		$fmw.fadeIn('fast');
		$fmw.draggable();
		$fmw.find('.fm-status .fa-close').click(function(e){
			e.preventDefault();
			$fmw.fadeOut('fast',function(){
				$fmw.remove();
			});
		});
		if(type == 'files'){
			fm.loadFiles($fmw, '/ajax/files/', 1, key);
		}else if(type == 'images'){
			fm.loadImages($fmw, '/ajax/images/', 1, key);
		}
	},
	loadFiles: function($fmw, url, fulload, key){
		$fmw.addClass('files');
		$.ajax({
			url: url
		}).done(function(data) {
			if(fulload == 1){
				var $content = $fmw.find('.fm-content').html('<br/><p style="text-align:center">Soubory se zobrazí po vybrání kategorie souborů (vlevo).</p>');
				var $tree = $fmw.find('.fm-tree').html($(data).find('td').first().html());
				$fmw.find(".fm-content, .fm-tree").mCustomScrollbar({
					autoDraggerLength: false,
					autoExpandScrollbar: false,
					mouseWheel:{ scrollAmount: 200 }
				});
				$.each($tree.find('a'), function(){
					$(this).click(function(e){
						e.preventDefault();
						$tree.find('li').removeClass('active');
						$(this).parent().addClass('active');
						$('.fm-folder').html('File manager - '+$(this).html());
						fm.loadFiles($fmw, $(this).attr('href'), 0, key);
					});
				});
			}else{
				var $content = $fmw.find('.fm-content');
				$content.find('.mCSB_container').html($(data).find('td').last().html());
				$fmw.find(".fm-content").mCustomScrollbar("scrollTo","first",{
					scrollInertia:0
				});
				$.each($content.find('.sort a'), function(){
					$(this).click(function(e){
						e.preventDefault();
						fm.loadFiles($fmw, $(this).attr('href'), 0, key);
					});
				});
				$.each($content.find('a.file'), function(){
					$(this).click(function(e){
						e.preventDefault();
						$(key).val($(this).attr('href'));
						$fmw.fadeOut('fast',function(){
							$fmw.remove();
						});
					});
				});
			}
		});
	},
	loadImages: function($fmw, url, fulload, key){
		$fmw.addClass('images');
		$.ajax({
			url: url
		}).done(function(data) {
			if(fulload == 1){
				var $content = $fmw.find('.fm-content').html('<br/><p style="text-align:center">Obrázky se zobrazí po vybrání kategorie obrázků (vlevo).</p>');
				var $tree = $fmw.find('.fm-tree').html($(data).find('td').first().html());
				$fmw.find(".fm-content, .fm-tree").mCustomScrollbar({
					autoDraggerLength: false,
					autoExpandScrollbar: false,
					mouseWheel:{ scrollAmount: 200 }
				});
				$.each($tree.find('a'), function(){
					$(this).click(function(e){
						e.preventDefault();
						$tree.find('li').removeClass('active');
						$(this).parent().addClass('active');
						$('.fm-folder').html('File manager - '+$(this).html());
						fm.loadImages($fmw, $(this).attr('href'), 0, key);
					});
				});
			}else{
				var $content = $fmw.find('.fm-content');
				$content.find('.mCSB_container').html($(data).find('td').last().html());
				$fmw.find(".fm-content").mCustomScrollbar("scrollTo","first",{
					scrollInertia:0
				});
				$.each($content.find('.sort a'), function(){
					$(this).click(function(e){
						e.preventDefault();
						fm.loadImages($fmw, $(this).attr('href'), 0, key);
					});
				});
				$.each($content.find('a.size'), function(){
					$(this).click(function(e){
						e.preventDefault();
						$(key).val($(this).attr('href'));
						$fmw.fadeOut('fast',function(){
							$fmw.remove();
						});
					});
				});
			}
		});
	}
};
var nicEditorSpecial = {
		buttons: {
			'special' : {name : __('Speciální znaky'), type: 'nicEditorSpecialButton'},
		}
}
var nicEditorSpecialButton = nicEditorButton.extend({
	createDialog: function($focused, selInst, editor){
		var $this = this;
		var $dialog = $('.specialDialog');
		if($dialog.length < 1){
			$('body').append('<div class="specialDialog" />');
			var $dialog = $('.specialDialog');
			$dialog.dialog({
				width: 600,
				height: 400,
				closeOnEscape: true,
				title: "Vložení speciálních znaků",
				resizable: true,
				autoOpen: false,
				buttons: [{
					text: "Zavřít",
					click: function(){
						$dialog.dialog('close');
					}
				}]
			});
			$dialog.append('<div class="item">&amp;</div><div class="item">&lt;</div><div class="item">&gt;</div><div class="item">&Agrave;</div><div class="item">&Aacute;</div><div class="item">&Acirc;</div><div class="item">&Atilde;</div><div class="item">&Auml;</div><div class="item">&Aring;</div><div class="item">&AElig;</div><div class="item">&Ccedil;</div><div class="item">&Egrave;</div><div class="item">&Eacute;</div><div class="item">&Ecirc;</div><div class="item">&Euml;</div><div class="item">&Igrave;</div><div class="item">&Iacute;</div><div class="item">&Icirc;</div><div class="item">&Iuml;</div><div class="item">&ETH;</div><div class="item">&Ntilde;</div><div class="item">&Ograve;</div><div class="item">&Oacute;</div><div class="item">&Ocirc;</div><div class="item">&Otilde;</div><div class="item">&Ouml;</div><div class="item">&Oslash;</div><div class="item">&Ugrave;</div><div class="item">&Uacute;</div><div class="item">&Ucirc;</div><div class="item">&Uuml;</div><div class="item">&Yacute;</div><div class="item">&THORN;</div><div class="item">&szlig;</div><div class="item">&agrave;</div><div class="item">&aacute;</div><div class="item">&acirc;</div><div class="item">&atilde;</div><div class="item">&auml;</div><div class="item">&aring;</div><div class="item">&aelig;</div><div class="item">&ccedil;</div><div class="item">&egrave;</div><div class="item">&eacute;</div><div class="item">&ecirc;</div><div class="item">&euml;</div><div class="item">&igrave;</div><div class="item">&iacute;</div><div class="item">&icirc;</div><div class="item">&iuml;</div><div class="item">&eth;</div><div class="item">&ntilde;</div><div class="item">&ograve;</div><div class="item">&oacute;</div><div class="item">&ocirc;</div><div class="item">&otilde;</div><div class="item">&ouml;</div><div class="item">&oslash;</div><div class="item">&ugrave;</div><div class="item">&uacute;</div><div class="item">&ucirc;</div><div class="item">&uuml;</div><div class="item">&yacute;</div><div class="item">&thorn;</div><div class="item">&yuml;</div><div class="item">&iexcl;</div><div class="item">&cent;</div><div class="item">&pound;</div><div class="item">&curren;</div><div class="item">&yen;</div><div class="item">&brvbar;</div><div class="item">&sect;</div><div class="item">&uml;</div><div class="item">&copy;</div><div class="item">&ordf;</div><div class="item">&laquo;</div><div class="item">&not;</div><div class="item">&reg;</div><div class="item">&macr;</div><div class="item">&deg;</div><div class="item">&plusmn;</div><div class="item">&sup2;</div><div class="item">&sup3;</div><div class="item">&acute;</div><div class="item">&micro;</div><div class="item">&para;</div><div class="item">&cedil;</div><div class="item">&sup1;</div><div class="item">&ordm;</div><div class="item">&raquo;</div><div class="item">&frac14;</div><div class="item">&frac12;</div><div class="item">&frac34;</div><div class="item">&iquest;</div><div class="item">&times;</div><div class="item">&divide;</div><div class="item">&forall;</div><div class="item">&part;</div><div class="item">&exist;</div><div class="item">&empty;</div><div class="item">&nabla;</div><div class="item">&isin;</div><div class="item">&notin;</div><div class="item">&ni;</div><div class="item">&prod;</div><div class="item">&sum;</div><div class="item">&minus;</div><div class="item">&lowast;</div><div class="item">&radic;</div><div class="item">&prop;</div><div class="item">&infin;</div><div class="item">&ang;</div><div class="item">&and;</div><div class="item">&or;</div><div class="item">&cap;</div><div class="item">&cup;</div><div class="item">&int;</div><div class="item">&there4;</div><div class="item">&sim;</div><div class="item">&cong;</div><div class="item">&asymp;</div><div class="item">&ne;</div><div class="item">&equiv;</div><div class="item">&le;</div><div class="item">&ge;</div><div class="item">&sub;</div><div class="item">&sup;</div><div class="item">&nsub;</div><div class="item">&sube;</div><div class="item">&supe;</div><div class="item">&oplus;</div><div class="item">&otimes;</div><div class="item">&perp;</div><div class="item">&sdot;</div><div class="item">&Alpha;</div><div class="item">&Beta;</div><div class="item">&Gamma;</div><div class="item">&Delta;</div><div class="item">&Epsilon;</div><div class="item">&Zeta;</div><div class="item">&Eta;</div><div class="item">&Theta;</div><div class="item">&Iota;</div><div class="item">&Kappa;</div><div class="item">&Lambda;</div><div class="item">&Mu;</div><div class="item">&Nu;</div><div class="item">&Xi;</div><div class="item">&Omicron;</div><div class="item">&Pi;</div><div class="item">&Rho;</div><div class="item">&Sigma;</div><div class="item">&Tau;</div><div class="item">&Upsilon;</div><div class="item">&Phi;</div><div class="item">&Chi;</div><div class="item">&Psi;</div><div class="item">&Omega;</div><div class="item">&alpha;</div><div class="item">&beta;</div><div class="item">&gamma;</div><div class="item">&delta;</div><div class="item">&epsilon;</div><div class="item">&zeta;</div><div class="item">&eta;</div><div class="item">&theta;</div><div class="item">&iota;</div><div class="item">&kappa;</div><div class="item">&lambda;</div><div class="item">&mu;</div><div class="item">&nu;</div><div class="item">&xi;</div><div class="item">&omicron;</div><div class="item">&pi;</div><div class="item">&rho;</div><div class="item">&sigmaf;</div><div class="item">&sigma;</div><div class="item">&sigma;</div><div class="item">&tau;</div><div class="item">&upsilon;</div><div class="item">&phi;</div><div class="item">&chi;</div><div class="item">&psi;</div><div class="item">&omega;</div><div class="item">&thetasym;</div><div class="item">&upsih;</div><div class="item">&piv;</div><div class="item">&OElig;</div><div class="item">&oelig;</div><div class="item">&Scaron;</div><div class="item">&scaron;</div><div class="item">&Yuml;</div><div class="item">&fnof;</div><div class="item">&circ;</div><div class="item">&tilde;</div><div class="item">&ndash;</div><div class="item">&mdash;</div><div class="item">&lsquo;</div><div class="item">&rsquo;</div><div class="item">&sbquo;</div><div class="item">&ldquo;</div><div class="item">&rdquo;</div><div class="item">&bdquo;</div><div class="item">&dagger;</div><div class="item">&Dagger;</div><div class="item">&bull;</div><div class="item">&hellip;</div><div class="item">&permil;</div><div class="item">&prime;</div><div class="item">&Prime;</div><div class="item">&lsaquo;</div><div class="item">&rsaquo;</div><div class="item">&oline;</div><div class="item">&euro;</div><div class="item">&trade;</div><div class="item">&larr;</div><div class="item">&uarr;</div><div class="item">&rarr;</div><div class="item">&darr;</div><div class="item">&harr;</div><div class="item">&crarr;</div><div class="item">&lceil;</div><div class="item">&rceil;</div><div class="item">&lfloor;</div><div class="item">&rfloor;</div><div class="item">&loz;</div><div class="item">&spades;</div><div class="item">&clubs;</div><div class="item">&hearts;</div><div class="item">&diams;</div>');		
			$dialog.append('<div class="item fa fa-500px"></div><div class="item fa fa-amazon"></div><div class="item fa fa-balance-scale"></div><div class="item fa fa-battery-0"></div><div class="item fa fa-battery-1"></div><div class="item fa fa-battery-2"></div><div class="item fa fa-battery-3"></div><div class="item fa fa-battery-4"></div><div class="item fa fa-battery-empty"></div><div class="item fa fa-battery-full"></div><div class="item fa fa-battery-half"></div><div class="item fa fa-battery-quarter"></div><div class="item fa fa-battery-three-quarters"></div><div class="item fa fa-black-tie"></div><div class="item fa fa-calendar-check-o"></div><div class="item fa fa-calendar-minus-o"></div><div class="item fa fa-calendar-plus-o"></div><div class="item fa fa-calendar-times-o"></div><div class="item fa fa-cc-diners-club"></div><div class="item fa fa-cc-jcb"></div><div class="item fa fa-chrome"></div><div class="item fa fa-clone"></div><div class="item fa fa-commenting"></div><div class="item fa fa-commenting-o"></div><div class="item fa fa-contao"></div><div class="item fa fa-creative-commons"></div><div class="item fa fa-expeditedssl"></div><div class="item fa fa-firefox"></div><div class="item fa fa-fonticons"></div><div class="item fa fa-genderless"></div><div class="item fa fa-get-pocket"></div><div class="item fa fa-gg"></div><div class="item fa fa-gg-circle"></div><div class="item fa fa-hand-grab-o"></div><div class="item fa fa-hand-lizard-o"></div><div class="item fa fa-hand-paper-o"></div><div class="item fa fa-hand-peace-o"></div><div class="item fa fa-hand-pointer-o"></div><div class="item fa fa-hand-rock-o"></div><div class="item fa fa-hand-scissors-o"></div><div class="item fa fa-hand-spock-o"></div><div class="item fa fa-hand-stop-o"></div><div class="item fa fa-hourglass"></div><div class="item fa fa-hourglass-1"></div><div class="item fa fa-hourglass-2"></div><div class="item fa fa-hourglass-3"></div><div class="item fa fa-hourglass-end"></div><div class="item fa fa-hourglass-half"></div><div class="item fa fa-hourglass-o"></div><div class="item fa fa-hourglass-start"></div><div class="item fa fa-houzz"></div><div class="item fa fa-i-cursor"></div><div class="item fa fa-industry"></div><div class="item fa fa-internet-explorer"></div><div class="item fa fa-map"></div><div class="item fa fa-map-o"></div><div class="item fa fa-map-pin"></div><div class="item fa fa-map-signs"></div><div class="item fa fa-mouse-pointer"></div><div class="item fa fa-object-group"></div><div class="item fa fa-object-ungroup"></div><div class="item fa fa-odnoklassniki"></div><div class="item fa fa-odnoklassniki-square"></div><div class="item fa fa-opencart"></div><div class="item fa fa-opera"></div><div class="item fa fa-optin-monster"></div><div class="item fa fa-registered"></div><div class="item fa fa-safari"></div><div class="item fa fa-sticky-note"></div><div class="item fa fa-sticky-note-o"></div><div class="item fa fa-television"></div><div class="item fa fa-trademark"></div><div class="item fa fa-tripadvisor"></div><div class="item fa fa-tv"></div><div class="item fa fa-vimeo"></div><div class="item fa fa-wikipedia-w"></div><div class="item fa fa-y-combinator"></div><div class="item fa fa-yc"></div><div class="item fa fa-adjust"></div><div class="item fa fa-anchor"></div><div class="item fa fa-archive"></div><div class="item fa fa-area-chart"></div><div class="item fa fa-arrows"></div><div class="item fa fa-arrows-h"></div><div class="item fa fa-arrows-v"></div><div class="item fa fa-asterisk"></div><div class="item fa fa-at"></div><div class="item fa fa-automobile"></div><div class="item fa fa-balance-scale"></div><div class="item fa fa-ban"></div><div class="item fa fa-bank"></div><div class="item fa fa-bar-chart"></div><div class="item fa fa-bar-chart-o"></div><div class="item fa fa-barcode"></div><div class="item fa fa-bars"></div><div class="item fa fa-battery-0"></div><div class="item fa fa-battery-1"></div><div class="item fa fa-battery-2"></div><div class="item fa fa-battery-3"></div><div class="item fa fa-battery-4"></div><div class="item fa fa-battery-empty"></div><div class="item fa fa-battery-full"></div><div class="item fa fa-battery-half"></div><div class="item fa fa-battery-quarter"></div><div class="item fa fa-battery-three-quarters"></div><div class="item fa fa-bed"></div><div class="item fa fa-beer"></div><div class="item fa fa-bell"></div><div class="item fa fa-bell-o"></div><div class="item fa fa-bell-slash"></div><div class="item fa fa-bell-slash-o"></div><div class="item fa fa-bicycle"></div><div class="item fa fa-binoculars"></div><div class="item fa fa-birthday-cake"></div><div class="item fa fa-bolt"></div><div class="item fa fa-bomb"></div><div class="item fa fa-book"></div><div class="item fa fa-bookmark"></div><div class="item fa fa-bookmark-o"></div><div class="item fa fa-briefcase"></div><div class="item fa fa-bug"></div><div class="item fa fa-building"></div><div class="item fa fa-building-o"></div><div class="item fa fa-bullhorn"></div><div class="item fa fa-bullseye"></div><div class="item fa fa-bus"></div><div class="item fa fa-cab"></div><div class="item fa fa-calculator"></div><div class="item fa fa-calendar"></div><div class="item fa fa-calendar-check-o"></div><div class="item fa fa-calendar-minus-o"></div><div class="item fa fa-calendar-o"></div><div class="item fa fa-calendar-plus-o"></div><div class="item fa fa-calendar-times-o"></div><div class="item fa fa-camera"></div><div class="item fa fa-camera-retro"></div><div class="item fa fa-car"></div><div class="item fa fa-caret-square-o-down"></div><div class="item fa fa-caret-square-o-left"></div><div class="item fa fa-caret-square-o-right"></div><div class="item fa fa-caret-square-o-up"></div><div class="item fa fa-cart-arrow-down"></div><div class="item fa fa-cart-plus"></div><div class="item fa fa-cc"></div><div class="item fa fa-certificate"></div><div class="item fa fa-check"></div><div class="item fa fa-check-circle"></div><div class="item fa fa-check-circle-o"></div><div class="item fa fa-check-square"></div><div class="item fa fa-check-square-o"></div><div class="item fa fa-child"></div><div class="item fa fa-circle"></div><div class="item fa fa-circle-o"></div><div class="item fa fa-circle-o-notch"></div><div class="item fa fa-circle-thin"></div><div class="item fa fa-clock-o"></div><div class="item fa fa-clone"></div><div class="item fa fa-close"></div><div class="item fa fa-cloud"></div><div class="item fa fa-cloud-download"></div><div class="item fa fa-cloud-upload"></div><div class="item fa fa-code"></div><div class="item fa fa-code-fork"></div><div class="item fa fa-coffee"></div><div class="item fa fa-cog"></div><div class="item fa fa-cogs"></div><div class="item fa fa-comment"></div><div class="item fa fa-comment-o"></div><div class="item fa fa-commenting"></div><div class="item fa fa-commenting-o"></div><div class="item fa fa-comments"></div><div class="item fa fa-comments-o"></div><div class="item fa fa-compass"></div><div class="item fa fa-copyright"></div><div class="item fa fa-creative-commons"></div><div class="item fa fa-credit-card"></div><div class="item fa fa-crop"></div><div class="item fa fa-crosshairs"></div><div class="item fa fa-cube"></div><div class="item fa fa-cubes"></div><div class="item fa fa-cutlery"></div><div class="item fa fa-dashboard"></div><div class="item fa fa-database"></div><div class="item fa fa-desktop"></div><div class="item fa fa-diamond"></div><div class="item fa fa-dot-circle-o"></div><div class="item fa fa-download"></div><div class="item fa fa-edit"></div><div class="item fa fa-ellipsis-h"></div><div class="item fa fa-ellipsis-v"></div><div class="item fa fa-envelope"></div><div class="item fa fa-envelope-o"></div><div class="item fa fa-envelope-square"></div><div class="item fa fa-eraser"></div><div class="item fa fa-exchange"></div><div class="item fa fa-exclamation"></div><div class="item fa fa-exclamation-circle"></div><div class="item fa fa-exclamation-triangle"></div><div class="item fa fa-external-link"></div><div class="item fa fa-external-link-square"></div><div class="item fa fa-eye"></div><div class="item fa fa-eye-slash"></div><div class="item fa fa-eyedropper"></div><div class="item fa fa-fax"></div><div class="item fa fa-feed"></div><div class="item fa fa-female"></div><div class="item fa fa-fighter-jet"></div><div class="item fa fa-file-archive-o"></div><div class="item fa fa-file-audio-o"></div><div class="item fa fa-file-code-o"></div><div class="item fa fa-file-excel-o"></div><div class="item fa fa-file-image-o"></div><div class="item fa fa-file-movie-o"></div><div class="item fa fa-file-pdf-o"></div><div class="item fa fa-file-photo-o"></div><div class="item fa fa-file-picture-o"></div><div class="item fa fa-file-powerpoint-o"></div><div class="item fa fa-file-sound-o"></div><div class="item fa fa-file-video-o"></div><div class="item fa fa-file-word-o"></div><div class="item fa fa-file-zip-o"></div><div class="item fa fa-film"></div><div class="item fa fa-filter"></div><div class="item fa fa-fire"></div><div class="item fa fa-fire-extinguisher"></div><div class="item fa fa-flag"></div><div class="item fa fa-flag-checkered"></div><div class="item fa fa-flag-o"></div><div class="item fa fa-flash"></div><div class="item fa fa-flask"></div><div class="item fa fa-folder"></div><div class="item fa fa-folder-o"></div><div class="item fa fa-folder-open"></div><div class="item fa fa-folder-open-o"></div><div class="item fa fa-frown-o"></div><div class="item fa fa-futbol-o"></div><div class="item fa fa-gamepad"></div><div class="item fa fa-gavel"></div><div class="item fa fa-gear"></div><div class="item fa fa-gears"></div><div class="item fa fa-gift"></div><div class="item fa fa-glass"></div><div class="item fa fa-globe"></div><div class="item fa fa-graduation-cap"></div><div class="item fa fa-group"></div><div class="item fa fa-hand-grab-o"></div><div class="item fa fa-hand-lizard-o"></div><div class="item fa fa-hand-paper-o"></div><div class="item fa fa-hand-peace-o"></div><div class="item fa fa-hand-pointer-o"></div><div class="item fa fa-hand-rock-o"></div><div class="item fa fa-hand-scissors-o"></div><div class="item fa fa-hand-spock-o"></div><div class="item fa fa-hand-stop-o"></div><div class="item fa fa-hdd-o"></div><div class="item fa fa-headphones"></div><div class="item fa fa-heart"></div><div class="item fa fa-heart-o"></div><div class="item fa fa-heartbeat"></div><div class="item fa fa-history"></div><div class="item fa fa-home"></div><div class="item fa fa-hotel"></div><div class="item fa fa-hourglass"></div><div class="item fa fa-hourglass-1"></div><div class="item fa fa-hourglass-2"></div><div class="item fa fa-hourglass-3"></div><div class="item fa fa-hourglass-end"></div><div class="item fa fa-hourglass-half"></div><div class="item fa fa-hourglass-o"></div><div class="item fa fa-hourglass-start"></div><div class="item fa fa-i-cursor"></div><div class="item fa fa-image"></div><div class="item fa fa-inbox"></div><div class="item fa fa-industry"></div><div class="item fa fa-info"></div><div class="item fa fa-info-circle"></div><div class="item fa fa-institution"></div><div class="item fa fa-key"></div><div class="item fa fa-keyboard-o"></div><div class="item fa fa-language"></div><div class="item fa fa-laptop"></div><div class="item fa fa-leaf"></div><div class="item fa fa-legal"></div><div class="item fa fa-lemon-o"></div><div class="item fa fa-level-down"></div><div class="item fa fa-level-up"></div><div class="item fa fa-life-bouy"></div><div class="item fa fa-life-buoy"></div><div class="item fa fa-life-ring"></div><div class="item fa fa-life-saver"></div><div class="item fa fa-lightbulb-o"></div><div class="item fa fa-line-chart"></div><div class="item fa fa-location-arrow"></div><div class="item fa fa-lock"></div><div class="item fa fa-magic"></div><div class="item fa fa-magnet"></div><div class="item fa fa-mail-forward"></div><div class="item fa fa-mail-reply"></div><div class="item fa fa-mail-reply-all"></div><div class="item fa fa-male"></div><div class="item fa fa-map"></div><div class="item fa fa-map-marker"></div><div class="item fa fa-map-o"></div><div class="item fa fa-map-pin"></div><div class="item fa fa-map-signs"></div><div class="item fa fa-meh-o"></div><div class="item fa fa-microphone"></div><div class="item fa fa-microphone-slash"></div><div class="item fa fa-minus"></div><div class="item fa fa-minus-circle"></div><div class="item fa fa-minus-square"></div><div class="item fa fa-minus-square-o"></div><div class="item fa fa-mobile"></div><div class="item fa fa-mobile-phone"></div><div class="item fa fa-money"></div><div class="item fa fa-moon-o"></div><div class="item fa fa-mortar-board"></div><div class="item fa fa-motorcycle"></div><div class="item fa fa-mouse-pointer"></div><div class="item fa fa-music"></div><div class="item fa fa-navicon"></div><div class="item fa fa-newspaper-o"></div><div class="item fa fa-object-group"></div><div class="item fa fa-object-ungroup"></div><div class="item fa fa-paint-brush"></div><div class="item fa fa-paper-plane"></div><div class="item fa fa-paper-plane-o"></div><div class="item fa fa-paw"></div><div class="item fa fa-pencil"></div><div class="item fa fa-pencil-square"></div><div class="item fa fa-pencil-square-o"></div><div class="item fa fa-phone"></div><div class="item fa fa-phone-square"></div><div class="item fa fa-photo"></div><div class="item fa fa-picture-o"></div><div class="item fa fa-pie-chart"></div><div class="item fa fa-plane"></div><div class="item fa fa-plug"></div><div class="item fa fa-plus"></div><div class="item fa fa-plus-circle"></div><div class="item fa fa-plus-square"></div><div class="item fa fa-plus-square-o"></div><div class="item fa fa-power-off"></div><div class="item fa fa-print"></div><div class="item fa fa-puzzle-piece"></div><div class="item fa fa-qrcode"></div><div class="item fa fa-question"></div><div class="item fa fa-question-circle"></div><div class="item fa fa-quote-left"></div><div class="item fa fa-quote-right"></div><div class="item fa fa-random"></div><div class="item fa fa-recycle"></div><div class="item fa fa-refresh"></div><div class="item fa fa-registered"></div><div class="item fa fa-remove"></div><div class="item fa fa-reorder"></div><div class="item fa fa-reply"></div><div class="item fa fa-reply-all"></div><div class="item fa fa-retweet"></div><div class="item fa fa-road"></div><div class="item fa fa-rocket"></div><div class="item fa fa-rss"></div><div class="item fa fa-rss-square"></div><div class="item fa fa-search"></div><div class="item fa fa-search-minus"></div><div class="item fa fa-search-plus"></div><div class="item fa fa-send"></div><div class="item fa fa-send-o"></div><div class="item fa fa-server"></div><div class="item fa fa-share"></div><div class="item fa fa-share-alt"></div><div class="item fa fa-share-alt-square"></div><div class="item fa fa-share-square"></div><div class="item fa fa-share-square-o"></div><div class="item fa fa-shield"></div><div class="item fa fa-ship"></div><div class="item fa fa-shopping-cart"></div><div class="item fa fa-sign-in"></div><div class="item fa fa-sign-out"></div><div class="item fa fa-signal"></div><div class="item fa fa-sitemap"></div><div class="item fa fa-sliders"></div><div class="item fa fa-smile-o"></div><div class="item fa fa-soccer-ball-o"></div><div class="item fa fa-sort"></div><div class="item fa fa-sort-alpha-asc"></div><div class="item fa fa-sort-alpha-desc"></div><div class="item fa fa-sort-amount-asc"></div><div class="item fa fa-sort-amount-desc"></div><div class="item fa fa-sort-asc"></div><div class="item fa fa-sort-desc"></div><div class="item fa fa-sort-down"></div><div class="item fa fa-sort-numeric-asc"></div><div class="item fa fa-sort-numeric-desc"></div><div class="item fa fa-sort-up"></div><div class="item fa fa-space-shuttle"></div><div class="item fa fa-spinner"></div><div class="item fa fa-spoon"></div><div class="item fa fa-square"></div><div class="item fa fa-square-o"></div><div class="item fa fa-star"></div><div class="item fa fa-star-half"></div><div class="item fa fa-star-half-empty"></div><div class="item fa fa-star-half-full"></div><div class="item fa fa-star-half-o"></div><div class="item fa fa-star-o"></div><div class="item fa fa-sticky-note"></div><div class="item fa fa-sticky-note-o"></div><div class="item fa fa-street-view"></div><div class="item fa fa-suitcase"></div><div class="item fa fa-sun-o"></div><div class="item fa fa-support"></div><div class="item fa fa-tablet"></div><div class="item fa fa-tachometer"></div><div class="item fa fa-tag"></div><div class="item fa fa-tags"></div><div class="item fa fa-tasks"></div><div class="item fa fa-taxi"></div><div class="item fa fa-television"></div><div class="item fa fa-terminal"></div><div class="item fa fa-thumb-tack"></div><div class="item fa fa-thumbs-down"></div><div class="item fa fa-thumbs-o-down"></div><div class="item fa fa-thumbs-o-up"></div><div class="item fa fa-thumbs-up"></div><div class="item fa fa-ticket"></div><div class="item fa fa-times"></div><div class="item fa fa-times-circle"></div><div class="item fa fa-times-circle-o"></div><div class="item fa fa-tint"></div><div class="item fa fa-toggle-down"></div><div class="item fa fa-toggle-left"></div><div class="item fa fa-toggle-off"></div><div class="item fa fa-toggle-on"></div><div class="item fa fa-toggle-right"></div><div class="item fa fa-toggle-up"></div><div class="item fa fa-trademark"></div><div class="item fa fa-trash"></div><div class="item fa fa-trash-o"></div><div class="item fa fa-tree"></div><div class="item fa fa-trophy"></div><div class="item fa fa-truck"></div><div class="item fa fa-tty"></div><div class="item fa fa-tv"></div><div class="item fa fa-umbrella"></div><div class="item fa fa-university"></div><div class="item fa fa-unlock"></div><div class="item fa fa-unlock-alt"></div><div class="item fa fa-unsorted"></div><div class="item fa fa-upload"></div><div class="item fa fa-user"></div><div class="item fa fa-user-plus"></div><div class="item fa fa-user-secret"></div><div class="item fa fa-user-times"></div><div class="item fa fa-users"></div><div class="item fa fa-video-camera"></div><div class="item fa fa-volume-down"></div><div class="item fa fa-volume-off"></div><div class="item fa fa-volume-up"></div><div class="item fa fa-warning"></div><div class="item fa fa-wheelchair"></div><div class="item fa fa-wifi"></div><div class="item fa fa-wrench"></div><div class="item fa fa-hand-grab-o"></div><div class="item fa fa-hand-lizard-o"></div><div class="item fa fa-hand-o-down"></div><div class="item fa fa-hand-o-left"></div><div class="item fa fa-hand-o-right"></div><div class="item fa fa-hand-o-up"></div><div class="item fa fa-hand-paper-o"></div><div class="item fa fa-hand-peace-o"></div><div class="item fa fa-hand-pointer-o"></div><div class="item fa fa-hand-rock-o"></div><div class="item fa fa-hand-scissors-o"></div><div class="item fa fa-hand-spock-o"></div><div class="item fa fa-hand-stop-o"></div><div class="item fa fa-thumbs-down"></div><div class="item fa fa-thumbs-o-down"></div><div class="item fa fa-thumbs-o-up"></div><div class="item fa fa-thumbs-up"></div><div class="item fa fa-ambulance"></div><div class="item fa fa-automobile"></div><div class="item fa fa-bicycle"></div><div class="item fa fa-bus"></div><div class="item fa fa-cab"></div><div class="item fa fa-car"></div><div class="item fa fa-fighter-jet"></div><div class="item fa fa-motorcycle"></div><div class="item fa fa-plane"></div><div class="item fa fa-rocket"></div><div class="item fa fa-ship"></div><div class="item fa fa-space-shuttle"></div><div class="item fa fa-subway"></div><div class="item fa fa-taxi"></div><div class="item fa fa-train"></div><div class="item fa fa-truck"></div><div class="item fa fa-wheelchair"></div><div class="item fa fa-genderless"></div><div class="item fa fa-intersex"></div><div class="item fa fa-mars"></div><div class="item fa fa-mars-double"></div><div class="item fa fa-mars-stroke"></div><div class="item fa fa-mars-stroke-h"></div><div class="item fa fa-mars-stroke-v"></div><div class="item fa fa-mercury"></div><div class="item fa fa-neuter"></div><div class="item fa fa-transgender"></div><div class="item fa fa-transgender-alt"></div><div class="item fa fa-venus"></div><div class="item fa fa-venus-double"></div><div class="item fa fa-venus-mars"></div><div class="item fa fa-File Type Icons"></div><div class="item fa fa-file"></div><div class="item fa fa-file-archive-o"></div><div class="item fa fa-file-audio-o"></div><div class="item fa fa-file-code-o"></div><div class="item fa fa-file-excel-o"></div><div class="item fa fa-file-image-o"></div><div class="item fa fa-file-movie-o"></div><div class="item fa fa-file-o"></div><div class="item fa fa-file-pdf-o"></div><div class="item fa fa-file-photo-o"></div><div class="item fa fa-file-picture-o"></div><div class="item fa fa-file-powerpoint-o"></div><div class="item fa fa-file-sound-o"></div><div class="item fa fa-file-text"></div><div class="item fa fa-file-text-o"></div><div class="item fa fa-file-video-o"></div><div class="item fa fa-file-word-o"></div><div class="item fa fa-file-zip-o"></div><div class="item fa fa-circle-o-notch"></div><div class="item fa fa-cog"></div><div class="item fa fa-gear"></div><div class="item fa fa-refresh"></div><div class="item fa fa-spinner"></div><div class="item fa fa-check-square"></div><div class="item fa fa-check-square-o"></div><div class="item fa fa-circle"></div><div class="item fa fa-circle-o"></div><div class="item fa fa-dot-circle-o"></div><div class="item fa fa-minus-square"></div><div class="item fa fa-minus-square-o"></div><div class="item fa fa-plus-square"></div><div class="item fa fa-plus-square-o"></div><div class="item fa fa-square"></div><div class="item fa fa-square-o"></div><div class="item fa fa-cc-amex"></div><div class="item fa fa-cc-diners-club"></div><div class="item fa fa-cc-discover"></div><div class="item fa fa-cc-jcb"></div><div class="item fa fa-cc-mastercard"></div><div class="item fa fa-cc-paypal"></div><div class="item fa fa-cc-stripe"></div><div class="item fa fa-cc-visa"></div><div class="item fa fa-credit-card"></div><div class="item fa fa-google-wallet"></div><div class="item fa fa-paypal"></div><div class="item fa fa-area-chart"></div><div class="item fa fa-bar-chart"></div><div class="item fa fa-bar-chart-o"></div><div class="item fa fa-line-chart"></div><div class="item fa fa-pie-chart"></div><div class="item fa fa-bitcoin"></div><div class="item fa fa-btc"></div><div class="item fa fa-cny"></div><div class="item fa fa-dollar"></div><div class="item fa fa-eur"></div><div class="item fa fa-euro"></div><div class="item fa fa-gbp"></div><div class="item fa fa-gg"></div><div class="item fa fa-gg-circle"></div><div class="item fa fa-ils"></div><div class="item fa fa-inr"></div><div class="item fa fa-jpy"></div><div class="item fa fa-krw"></div><div class="item fa fa-money"></div><div class="item fa fa-rmb"></div><div class="item fa fa-rouble"></div><div class="item fa fa-rub"></div><div class="item fa fa-ruble"></div><div class="item fa fa-rupee"></div><div class="item fa fa-shekel"></div><div class="item fa fa-sheqel"></div><div class="item fa fa-try"></div><div class="item fa fa-turkish-lira"></div><div class="item fa fa-usd"></div><div class="item fa fa-won"></div><div class="item fa fa-yen"></div><div class="item fa fa-align-center"></div><div class="item fa fa-align-justify"></div><div class="item fa fa-align-left"></div><div class="item fa fa-align-right"></div><div class="item fa fa-bold"></div><div class="item fa fa-chain"></div><div class="item fa fa-chain-broken"></div><div class="item fa fa-clipboard"></div><div class="item fa fa-columns"></div><div class="item fa fa-copy"></div><div class="item fa fa-cut"></div><div class="item fa fa-dedent"></div><div class="item fa fa-eraser"></div><div class="item fa fa-file"></div><div class="item fa fa-file-o"></div><div class="item fa fa-file-text"></div><div class="item fa fa-file-text-o"></div><div class="item fa fa-files-o"></div><div class="item fa fa-floppy-o"></div><div class="item fa fa-font"></div><div class="item fa fa-header"></div><div class="item fa fa-indent"></div><div class="item fa fa-italic"></div><div class="item fa fa-link"></div><div class="item fa fa-list"></div><div class="item fa fa-list-alt"></div><div class="item fa fa-list-ol"></div><div class="item fa fa-list-ul"></div><div class="item fa fa-outdent"></div><div class="item fa fa-paperclip"></div><div class="item fa fa-paragraph"></div><div class="item fa fa-paste"></div><div class="item fa fa-repeat"></div><div class="item fa fa-rotate-left"></div><div class="item fa fa-rotate-right"></div><div class="item fa fa-save"></div><div class="item fa fa-scissors"></div><div class="item fa fa-strikethrough"></div><div class="item fa fa-subscript"></div><div class="item fa fa-superscript"></div><div class="item fa fa-table"></div><div class="item fa fa-text-height"></div><div class="item fa fa-text-width"></div><div class="item fa fa-th"></div><div class="item fa fa-th-large"></div><div class="item fa fa-th-list"></div><div class="item fa fa-underline"></div><div class="item fa fa-undo"></div><div class="item fa fa-unlink"></div><div class="item fa fa-angle-double-down"></div><div class="item fa fa-angle-double-left"></div><div class="item fa fa-angle-double-right"></div><div class="item fa fa-angle-double-up"></div><div class="item fa fa-angle-down"></div><div class="item fa fa-angle-left"></div><div class="item fa fa-angle-right"></div><div class="item fa fa-angle-up"></div><div class="item fa fa-arrow-circle-down"></div><div class="item fa fa-arrow-circle-left"></div><div class="item fa fa-arrow-circle-o-down"></div><div class="item fa fa-arrow-circle-o-left"></div><div class="item fa fa-arrow-circle-o-right"></div><div class="item fa fa-arrow-circle-o-up"></div><div class="item fa fa-arrow-circle-right"></div><div class="item fa fa-arrow-circle-up"></div><div class="item fa fa-arrow-down"></div><div class="item fa fa-arrow-left"></div><div class="item fa fa-arrow-right"></div><div class="item fa fa-arrow-up"></div><div class="item fa fa-arrows"></div><div class="item fa fa-arrows-alt"></div><div class="item fa fa-arrows-h"></div><div class="item fa fa-arrows-v"></div><div class="item fa fa-caret-down"></div><div class="item fa fa-caret-left"></div><div class="item fa fa-caret-right"></div><div class="item fa fa-caret-square-o-down"></div><div class="item fa fa-caret-square-o-left"></div><div class="item fa fa-caret-square-o-right"></div><div class="item fa fa-caret-square-o-up"></div><div class="item fa fa-caret-up"></div><div class="item fa fa-chevron-circle-down"></div><div class="item fa fa-chevron-circle-left"></div><div class="item fa fa-chevron-circle-right"></div><div class="item fa fa-chevron-circle-up"></div><div class="item fa fa-chevron-down"></div><div class="item fa fa-chevron-left"></div><div class="item fa fa-chevron-right"></div><div class="item fa fa-chevron-up"></div><div class="item fa fa-exchange"></div><div class="item fa fa-hand-o-down"></div><div class="item fa fa-hand-o-left"></div><div class="item fa fa-hand-o-right"></div><div class="item fa fa-hand-o-up"></div><div class="item fa fa-long-arrow-down"></div><div class="item fa fa-long-arrow-left"></div><div class="item fa fa-long-arrow-right"></div><div class="item fa fa-long-arrow-up"></div><div class="item fa fa-toggle-down"></div><div class="item fa fa-toggle-left"></div><div class="item fa fa-toggle-right"></div><div class="item fa fa-toggle-up"></div><div class="item fa fa-arrows-alt"></div><div class="item fa fa-backward"></div><div class="item fa fa-compress"></div><div class="item fa fa-eject"></div><div class="item fa fa-expand"></div><div class="item fa fa-fast-backward"></div><div class="item fa fa-fast-forward"></div><div class="item fa fa-forward"></div><div class="item fa fa-pause"></div><div class="item fa fa-play"></div><div class="item fa fa-play-circle"></div><div class="item fa fa-play-circle-o"></div><div class="item fa fa-random"></div><div class="item fa fa-step-backward"></div><div class="item fa fa-step-forward"></div><div class="item fa fa-stop"></div><div class="item fa fa-youtube-play"></div><div class="item fa fa-500px"></div><div class="item fa fa-adn"></div><div class="item fa fa-amazon"></div><div class="item fa fa-android"></div><div class="item fa fa-angellist"></div><div class="item fa fa-apple"></div><div class="item fa fa-behance"></div><div class="item fa fa-behance-square"></div><div class="item fa fa-bitbucket"></div><div class="item fa fa-bitbucket-square"></div><div class="item fa fa-bitcoin"></div><div class="item fa fa-black-tie"></div><div class="item fa fa-btc"></div><div class="item fa fa-buysellads"></div><div class="item fa fa-cc-amex"></div><div class="item fa fa-cc-diners-club"></div><div class="item fa fa-cc-discover"></div><div class="item fa fa-cc-jcb"></div><div class="item fa fa-cc-mastercard"></div><div class="item fa fa-cc-paypal"></div><div class="item fa fa-cc-stripe"></div><div class="item fa fa-cc-visa"></div><div class="item fa fa-chrome"></div><div class="item fa fa-codepen"></div><div class="item fa fa-connectdevelop"></div><div class="item fa fa-contao"></div><div class="item fa fa-css3"></div><div class="item fa fa-dashcube"></div><div class="item fa fa-delicious"></div><div class="item fa fa-deviantart"></div><div class="item fa fa-digg"></div><div class="item fa fa-dribbble"></div><div class="item fa fa-dropbox"></div><div class="item fa fa-drupal"></div><div class="item fa fa-empire"></div><div class="item fa fa-expeditedssl"></div><div class="item fa fa-facebook"></div><div class="item fa fa-facebook-f"></div><div class="item fa fa-facebook-official"></div><div class="item fa fa-facebook-square"></div><div class="item fa fa-firefox"></div><div class="item fa fa-flickr"></div><div class="item fa fa-fonticons"></div><div class="item fa fa-forumbee"></div><div class="item fa fa-foursquare"></div><div class="item fa fa-ge"></div><div class="item fa fa-get-pocket"></div><div class="item fa fa-gg"></div><div class="item fa fa-gg-circle"></div><div class="item fa fa-git"></div><div class="item fa fa-git-square"></div><div class="item fa fa-github"></div><div class="item fa fa-github-alt"></div><div class="item fa fa-github-square"></div><div class="item fa fa-gittip"></div><div class="item fa fa-google"></div><div class="item fa fa-google-plus"></div><div class="item fa fa-google-plus-square"></div><div class="item fa fa-google-wallet"></div><div class="item fa fa-gratipay"></div><div class="item fa fa-hacker-news"></div><div class="item fa fa-houzz"></div><div class="item fa fa-html5"></div><div class="item fa fa-instagram"></div><div class="item fa fa-internet-explorer"></div><div class="item fa fa-ioxhost"></div><div class="item fa fa-joomla"></div><div class="item fa fa-jsfiddle"></div><div class="item fa fa-lastfm"></div><div class="item fa fa-lastfm-square"></div><div class="item fa fa-leanpub"></div><div class="item fa fa-linkedin"></div><div class="item fa fa-linkedin-square"></div><div class="item fa fa-linux"></div><div class="item fa fa-maxcdn"></div><div class="item fa fa-meanpath"></div><div class="item fa fa-medium"></div><div class="item fa fa-odnoklassniki"></div><div class="item fa fa-odnoklassniki-square"></div><div class="item fa fa-opencart"></div><div class="item fa fa-openid"></div><div class="item fa fa-opera"></div><div class="item fa fa-optin-monster"></div><div class="item fa fa-pagelines"></div><div class="item fa fa-paypal"></div><div class="item fa fa-pied-piper"></div><div class="item fa fa-pied-piper-alt"></div><div class="item fa fa-pinterest"></div><div class="item fa fa-pinterest-p"></div><div class="item fa fa-pinterest-square"></div><div class="item fa fa-qq"></div><div class="item fa fa-ra"></div><div class="item fa fa-rebel"></div><div class="item fa fa-reddit"></div><div class="item fa fa-reddit-square"></div><div class="item fa fa-renren"></div><div class="item fa fa-safari"></div><div class="item fa fa-sellsy"></div><div class="item fa fa-share-alt"></div><div class="item fa fa-share-alt-square"></div><div class="item fa fa-shirtsinbulk"></div><div class="item fa fa-simplybuilt"></div><div class="item fa fa-skyatlas"></div><div class="item fa fa-skype"></div><div class="item fa fa-slack"></div><div class="item fa fa-slideshare"></div><div class="item fa fa-soundcloud"></div><div class="item fa fa-spotify"></div><div class="item fa fa-stack-exchange"></div><div class="item fa fa-stack-overflow"></div><div class="item fa fa-steam"></div><div class="item fa fa-steam-square"></div><div class="item fa fa-stumbleupon"></div><div class="item fa fa-stumbleupon-circle"></div><div class="item fa fa-tencent-weibo"></div><div class="item fa fa-trello"></div><div class="item fa fa-tripadvisor"></div><div class="item fa fa-tumblr"></div><div class="item fa fa-tumblr-square"></div><div class="item fa fa-twitch"></div><div class="item fa fa-twitter"></div><div class="item fa fa-twitter-square"></div><div class="item fa fa-viacoin"></div><div class="item fa fa-vimeo"></div><div class="item fa fa-vimeo-square"></div><div class="item fa fa-vine"></div><div class="item fa fa-vk"></div><div class="item fa fa-wechat"></div><div class="item fa fa-weibo"></div><div class="item fa fa-weixin"></div><div class="item fa fa-whatsapp"></div><div class="item fa fa-wikipedia-w"></div><div class="item fa fa-windows"></div><div class="item fa fa-wordpress"></div><div class="item fa fa-xing"></div><div class="item fa fa-xing-square"></div><div class="item fa fa-y-combinator"></div><div class="item fa fa-y-combinator-square"></div><div class="item fa fa-yahoo"></div><div class="item fa fa-yc"></div><div class="item fa fa-yc-square"></div><div class="item fa fa-yelp"></div><div class="item fa fa-youtube"></div><div class="item fa fa-youtube-play"></div><div class="item fa fa-youtube-square"></div><div class="item fa fa-ambulance"></div><div class="item fa fa-h-square"></div><div class="item fa fa-heart"></div><div class="item fa fa-heart-o"></div><div class="item fa fa-heartbeat"></div><div class="item fa fa-hospital-o"></div><div class="item fa fa-medkit"></div><div class="item fa fa-plus-square"></div><div class="item fa fa-stethoscope"></div><div class="item fa fa-user-md"></div><div class="item fa fa-wheelchair"></div>');			
			$dialog.find('.item').click(function(){
				selInst.restoreRng();
				$focused.focus();
				if($(this).hasClass('fa')){
					var html = '<em class="'+$(this).attr('class')+'"></em>';
					editor.nicCommand('insertHTML', html);
				}else{
					editor.nicCommand('insertHTML', $(this).html());
				}
				$dialog.dialog('close');
			});
		}
		$dialog.dialog('open');
	},
	mouseClick: function() {
		var editor = this.ne;
		var selInst = this.ne.selectedInstance;
		selInst.saveRng();
		this.ne.selectedInstance.saveRng(); 
		var $focused = $(document.activeElement);
		this.createDialog($focused, selInst, editor); 
	}
});
nicEditors.registerPlugin(nicPlugin, nicEditorSpecial);
var nicEditorImage = {
		buttons: {
			'image' : {name : __('Vložit obrázek'), type: 'nicEditorImageButton'},
		}
}
var nicEditorImageButton = nicEditorButton.extend({
	submit : function($focused, selInst, editor) {
		this.ne.selectedInstance.restoreRng();
		this.ln = this.ne.selectedInstance.selElm().parentTag('IMG');
		var $dialog = $('.imageDialog');
		if(this.ln) {
			$(this.ln).attr('src',$dialog.find('#imgSrc_0').val());
			$(this.ln).attr('alt',$dialog.find('#imgAlt_0').val());
		}else{
			selInst.restoreRng();
			var html = '<img src="'+$dialog.find('#imgSrc_0').val()+'" alt="'+$dialog.find('#imgAlt_0').val()+'" />';
			$focused.focus();
			editor.nicCommand('insertHTML', html);
		}	
		$dialog.dialog("close");
		this.ne.selectedInstance.restoreRng();
	},
	createDialog: function($focused, selInst, editor){
		var $this = this;
		var $dialog = $('.imageDialog');
		if($dialog.length < 1){
			$('body').append('<div class="imageDialog" />');
			var $dialog = $('.imageDialog');
			$dialog.dialog({
				width: 400,
				height: 400,
				closeOnEscape: true,
				title: "Vložení obrázku",
				resizable: true,
				autoOpen: false,
				buttons: [{
					text: "Uložit",
					click: function(e){
						$focused.focus();
						$this.submit($focused, selInst, editor);
					}
				},
				{
					text: "Zavřít",
					click: function(){
						$dialog.dialog('close');
					}
				}]
			});
			$dialog.append('<label for="imgSrc_0">Cesta k obrázku</label><input type="text" id="imgSrc_0" name="imgSrc_0" /><button id="fm_4" class="browseImages ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">Vybrat obrázek</button><label for="imgAlt_0">Název obrázku</label><input type="text" id="imgAlt_0" name="imgAlt_0" />');		
			fm.init('images', '#imgSrc_0', '#fm_4');
		}
		var $link = $(this.ne.selectedInstance.selElm());			
		if($link.length > 0){
			$dialog.find('#imgSrc_0').val($link.attr('src'));
			$dialog.find('#imgAlt_0').val($link.attr('alt'));
		}
		$dialog.dialog('open');
	},
	mouseClick: function() {
		var editor = this.ne;
		var selInst = this.ne.selectedInstance;
		selInst.saveRng();
		this.ne.selectedInstance.saveRng();
		var $focused = $(document.activeElement);
		this.createDialog($focused, selInst, editor);
	}
});
nicEditors.registerPlugin(nicPlugin, nicEditorImage);
var nicEditorTemplates = {
		buttons: {
			'templates' : {name : __('Šablony'), type: 'nicEditorTemplateButton'},
		}
}
var nicEditorTemplateButton = nicEditorButton.extend({
	createDialog: function($focused, selInst, editor){
		var $this = this;
		var $dialog = $('.templateDialog');
		if($dialog.length < 1){
			$('body').append('<div class="templateDialog" />');
			var $dialog = $('.templateDialog');
			$dialog.dialog({
				width: 600,
				height: 400,
				closeOnEscape: true,
				title: "Dostupné šablony",
				resizable: false,
				autoOpen: false,
				buttons: [{
					text: "Zavřít",
					click: function(){
						$dialog.dialog('close');
					}
				}]
			});
			this.loadContent('/ajax/templates/', $dialog, selInst, editor, $focused);
		}
		$dialog.dialog('open');
	},
	loadContent: function(url, $dialog, selInst, editor, $focused){
		$.ajax({
			url: url
		}).done(function(data) {
			$dialog.append(data);
			$dialog.find('div.item').click(function(){
				selInst.restoreRng();
				var html = decodeURIComponent($(this).data('html'));
				$focused.focus();
				editor.nicCommand('insertHTML', html);
				$dialog.dialog('close');
			});
		});
	},
	mouseClick: function() {
		var editor = this.ne;
		var selInst = this.ne.selectedInstance;
		selInst.saveRng();
		this.ne.selectedInstance.saveRng(); 
		var $focused = $(document.activeElement); 
		this.createDialog($focused, selInst, editor);
	}
});
nicEditors.registerPlugin(nicPlugin, nicEditorTemplates);
var nicCodeButton = nicEditorAdvancedButton.extend({
	width : '350px',
	addPane : function() {
		this.removePane();
	},
	submit : function($focused, selInst, editor, $dialog) {
		$focused.focus();
		var code = $('.codeDialog').find('textarea').val();
		selInst.setContent(code);
		$dialog.dialog('close');
	},
	mouseClick: function(){
		var editor = this.ne;
		var selInst = editor.selectedInstance;
		selInst.saveRng();
		var $focused = $(document.activeElement);
		this.createDialog($focused, editor, selInst);
	},
	createDialog: function($focused, editor, selInst){
		var $this = this;
		var $dialog = $('.codeDialog');
    var blockHtmlElements = 'h[1-6]|div|p|blockquote|pre|form|label|select|input|button|ol|ul|dd|dt|li|table|thead|tbody|td|th|tr|hr|br';
    var html = selInst.getContent();
    html = html.replace(new RegExp('(</?(' + blockHtmlElements + ')[^<]*?>)\\s*', 'gi'), '$1\n')
					     .replace(new RegExp('\\s*(</?(' + blockHtmlElements + ')[^<]*?>)', 'gi'), '\n$1');
		if($dialog.length < 1){
			$('body').append('<div class="codeDialog" />');
			var $dialog = $('.codeDialog');
			$dialog.dialog({
				width: 900,
				height: 500,
				closeOnEscape: true,
				title: "Editace HTML",
				resizable: false,
				autoOpen: false,
				buttons: [{
					text: "Uložit",
					click: function(e){
						$focused.focus();
						$this.submit($focused, selInst, editor, $dialog);
					}
				},
				{
					text: "Zavřít",
					click: function(){
						$dialog.dialog('close');
					}
				}]
			});	
			$dialog.append('<textarea id="code_0">'+html+'</textarea>');		
    }else{	
      $dialog.find('#code_0').val(html);
		}
		$dialog.dialog('open');		
	}
});
nicEditors.registerPlugin(nicPlugin, nicSelectOptions);
nicEditors.registerPlugin(nicPlugin, nicEditorConfig);