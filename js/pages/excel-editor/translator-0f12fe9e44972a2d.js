(self.webpackChunk_N_E=self.webpackChunk_N_E||[]).push([[7243],{75783:function(e,t,n){"use strict";n.d(t,{Z:function(){return f}});var o=n(59499),r=n(4730),i=n(67294),a=n(87108),d=n(85893),l=["width","minWidth","maxWidth","className","style","children"];function s(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function u(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?s(Object(n),!0).forEach((function(t){(0,o.Z)(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):s(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var c=a.ZP.div.withConfig({displayName:"ResizableTh__Resizer",componentId:"sc-i1kr9d-0"})(["display:none;border-right:3px double #d0d0d0;height:20px;position:absolute;right:3px;top:50%;box-sizing:border-box;transform:translate(50%,-50%);z-index:1;touch-action:none;&:hover{cursor:col-resize;}"]),p=a.ZP.th.withConfig({displayName:"ResizableTh__Th",componentId:"sc-i1kr9d-1"})(["user-select:none;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;height:43px;position:relative;&:hover{","{display:inline-block;}}"],c);function f(e){var t=e.width,n=e.minWidth,o=e.maxWidth,a=e.className,s=e.style,f=e.children,x=(0,r.Z)(e,l),v=(0,i.useState)(t||100),h=v[0],m=v[1],w=n||40,_=o||1e3;return(0,d.jsxs)(p,u(u({className:a,style:u({width:h},s)},x),{},{children:[(0,d.jsx)(c,{onTouchStart:function(e){var t=h,n=e.changedTouches[0].pageX;function o(e){var o=t-n+e.changedTouches[0].pageX;m(o<w?w:o>_?_:o)}document.body.addEventListener("touchmove",o),document.body.addEventListener("touchend",(function(){document.body.removeEventListener("touchmove",o)}),{once:!0})},onMouseDown:function(e){var t=h,n=e.pageX;function o(e){var o=t-n+e.pageX;m(o<w?w:o>_?_:o)}document.body.addEventListener("mousemove",o),document.body.addEventListener("mouseup",(function(){document.body.removeEventListener("mousemove",o)}),{once:!0})}}),f]}))}},66312:function(e,t,n){"use strict";n.r(t),n.d(t,{default:function(){return V}});var o=n(50029),r=n(87794),i=n.n(r),a=n(9008),d=n(11163),l=n(67294),s=n(14416),u=n(21920),c=n(19772),p=n(3294),f=n(13897),x=n(75783),v=n(87108),h=v.ZP.div.withConfig({displayName:"FdPreviewstyled__Container",componentId:"sc-14lrydp-0"})(["margin-top:20px;padding:0 20px;@media all and (max-width:992px){padding:0 10px;}> .wrapper{padding:20px;background:#fff;border:none;border-radius:15px;box-shadow:var(--defaultBoxShadow3);> .wrapper__title{font-size:20px;font-weight:600;}}"]),m=v.ZP.div.withConfig({displayName:"FdPreviewstyled__UploadExcelPreviewContainer",componentId:"sc-14lrydp-1"})(["margin-top:20px;> .wrapper{> .wrapper__title{font-size:16px;font-weight:600;margin-bottom:10px;}> .wrapper__emptyTableWrapper{display:flex;align-items:center;justify-content:center;border:1px dashed #e0e0e0;border-radius:5px;background:#ffffff;min-height:200px;max-height:300px;font-size:16px;font-weight:600;}> .wrapper__tableWrapper{overflow-x:auto;border:1px solid #e0e0e0;border-radius:5px;background:#ffffff;min-height:200px;max-height:300px;&::-webkit-scrollbar{background:#e1e1e130;height:5px;width:5px;}&::-webkit-scrollbar-track{border-radius:10px;}&::-webkit-scrollbar-thumb{background-color:#00000010;border-radius:10px;}table{position:relative;text-align:center;width:fit-content;table-layout:fixed;border:none;> thead{> tr{> th{height:35px;width:40px;box-sizing:border-box;padding:0 5px;background:#efefef;color:#000;font-weight:600;position:sticky;top:0;border-bottom:1px solid #f0f0f0;border-right:1px solid #f0f0f0;line-height:1.5;font-size:12px;.numbering__box{font-size:10px;border-bottom:1px solid #f7f7f7;padding:5px 0;color:#777;word-break:keep-all;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}.value__box{padding:5px 0;word-break:keep-all;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}}}}> tbody{> tr{&:hover{background:#f8f8f8;.fixed-col-left{background:#f8f8f8;}}> .numbering__td{font-size:10px;color:#777;background:#efefef;font-weight:600;}> td{box-sizing:border-box;padding:5px;border-bottom:1px solid #f7f7f7;line-height:1.5;word-break:keep-all;white-space:pre-line;font-size:12px;color:#000;font-weight:500;.thumbnail-figure{width:55%;height:55%;border:1px solid #f0f0f0;border-radius:10px;overflow:hidden;margin-left:auto;margin-right:auto;}.content-box{padding:5px 0;div{white-space:pre-line;}.stockRegisterStatusView-button-item{margin:0;padding:0 16px;width:auto;height:30px;margin-left:auto;margin-right:auto;border-radius:10px;color:#222;border:none;background:var(--grayButtonColor);font-size:12px;font-weight:700;}.optionCodeText{cursor:pointer;&:hover{font-weight:500;color:var(--mainColor);}}}}}}}.tr-selected{background:var(--defaultBlueColorOpacity100);&:hover{background:var(--defaultBlueColorOpacity200);.fixed-col-left{background:var(--defaultBlueColorOpacity200);}}}table .fixed-col-left{position:sticky;left:0;z-index:10;border-right:1px solid #e0e0e060;box-shadow:6px 0 5px -7px #e0e0e0;}.status-button{height:30px;width:150px;padding:0;margin:auto;font-size:12px;}.delete-button-item{width:30px;height:30px;margin:0;padding:0;margin-left:auto;margin-right:auto;border-radius:5px;.icon-figure{width:70%;height:70%;margin-left:auto;margin-right:auto;}}}}"]),w=v.ZP.div.withConfig({displayName:"FdPreviewstyled__Arrow",componentId:"sc-14lrydp-2"})(["margin-top:20px;padding:10px 0;width:40px;margin-left:auto;margin-right:auto;"]),_=n(85893);function g(e){var t=e.selectedExcelTranslator,n=e.uploadHeaderList,o=e.uploadRowDataList,r=e.downloadHeaderList,i=e.downloadRowDataList;return(0,_.jsx)(_.Fragment,{children:(0,_.jsx)(h,{children:(0,_.jsxs)("div",{className:"wrapper",children:[(0,_.jsx)("div",{className:"wrapper__title",children:"\ubbf8\ub9ac\ubcf4\uae30"}),(0,_.jsx)(b,{uploadHeaderList:n,uploadRowDataList:o}),(0,_.jsx)(w,{children:(0,_.jsx)(f.Z,{src:"/images/icon/arrow_downward_808080.svg"})}),(0,_.jsx)(y,{selectedExcelTranslator:t,downloadHeaderList:r,downloadRowDataList:i})]})})})}function b(e){var t=e.uploadHeaderList,n=e.uploadRowDataList;return(0,_.jsx)(_.Fragment,{children:(0,_.jsx)(m,{children:(0,_.jsxs)("div",{className:"wrapper",children:[(0,_.jsx)("div",{className:"wrapper__title",children:"\ubcc0\ud658\ud560 \uc5d1\uc140"}),!t&&(0,_.jsx)("div",{className:"wrapper__emptyTableWrapper",children:"\ubcc0\ud658\ud560 \uc5d1\uc140\uc744 \uc120\ud0dd\ud574 \uc8fc\uc138\uc694."}),t&&(0,_.jsx)("div",{className:"wrapper__tableWrapper",children:(0,_.jsxs)("table",{cellSpacing:0,children:[(0,_.jsx)("thead",{children:(0,_.jsxs)("tr",{children:[(0,_.jsx)(x.Z,{className:"fixed-col-left",width:40,style:{zIndex:11}}),null===t||void 0===t?void 0:t.map((function(e,t){return(0,_.jsxs)(x.Z,{scope:"col",width:150,style:{zIndex:"10"},children:[(0,_.jsxs)("div",{className:"numbering__box",children:[t+1,"\uc5f4"]}),(0,_.jsx)("div",{className:"value__box",children:null===e||void 0===e?void 0:e.headerName})]},null===e||void 0===e?void 0:e.id)}))]})}),(0,_.jsx)("tbody",{children:null===n||void 0===n?void 0:n.map((function(e,t){var n;return(0,_.jsxs)("tr",{children:[(0,_.jsxs)("td",{className:"numbering__td fixed-col-left",children:[t+1,"\ud589"]}),null===e||void 0===e||null===(n=e.uploadCellValueList)||void 0===n?void 0:n.map((function(e){return(0,_.jsx)("td",{children:null===e||void 0===e?void 0:e.value},null===e||void 0===e?void 0:e.id)}))]},null===e||void 0===e?void 0:e.id)}))})]})})]})})})}function y(e){var t,n=e.selectedExcelTranslator,o=(e.downloadHeaderList,e.downloadRowDataList);return(0,_.jsx)(_.Fragment,{children:(0,_.jsx)(m,{children:(0,_.jsxs)("div",{className:"wrapper",children:[(0,_.jsx)("div",{className:"wrapper__title",children:"\uacb0\uacfc \uc5d1\uc140"}),(0,_.jsx)("div",{className:"wrapper__tableWrapper",children:(0,_.jsxs)("table",{cellSpacing:0,children:[(0,_.jsx)("thead",{children:(0,_.jsxs)("tr",{children:[(0,_.jsx)(x.Z,{className:"fixed-col-left",width:40,style:{zIndex:11}}),null===n||void 0===n||null===(t=n.excelTranslatorDownloadHeaderList)||void 0===t?void 0:t.map((function(e,t){return(0,_.jsxs)(x.Z,{scope:"col",width:200,style:{zIndex:"10"},children:[(0,_.jsxs)("div",{className:"numbering__box",children:[t+1,"\uc5f4"]}),(0,_.jsx)("div",{className:"value__box",children:null===e||void 0===e?void 0:e.headerName})]},null===e||void 0===e?void 0:e.id)}))]})}),(0,_.jsx)("tbody",{children:null===o||void 0===o?void 0:o.map((function(e,t){var n;return(0,_.jsxs)("tr",{children:[(0,_.jsxs)("td",{className:"numbering__td fixed-col-left",children:[t+1,"\ud589"]}),null===e||void 0===e||null===(n=e.downloadCellValueList)||void 0===n?void 0:n.map((function(e){return(0,_.jsx)("td",{children:null===e||void 0===e?void 0:e.value},null===e||void 0===e?void 0:e.id)}))]},null===e||void 0===e?void 0:e.id)}))})]})})]})})})}var L=n(92892),j=n(99499),D=v.ZP.div.withConfig({displayName:"FdSelectorstyled__Container",componentId:"sc-1xqwcyw-0"})(["margin-top:20px;padding:0 20px;@media all and (max-width:992px){padding:0 10px;}.wrapper{border-radius:15px;background:#fff;padding:20px;box-shadow:var(--defaultBoxShadow3);&__title{font-size:20px;font-weight:600;}&__body{margin-top:20px;display:flex;flex-direction:row;gap:10px;@media all and (max-width:992px){flex-direction:column;}select{width:300px;background-color:var(--defaultBlueColorOpacity100);border-radius:10px;border:none;font-weight:500;@media all and (max-width:992px){width:100%;}}&__buttonGroup{display:flex;flex-direction:row;gap:10px;&__textButton{width:48px;height:48px;background:var(--grayButtonColor);font-weight:700;font-size:14px;border:none;border-radius:10px;@media all and (max-width:992px){width:100%;}}&__iconButton{width:48px;height:48px;background:var(--grayButtonColor);font-weight:700;font-size:14px;border:none;border-radius:10px;> .iconFigure{width:24px;margin-left:auto;margin-right:auto;}@media all and (max-width:992px){width:100%;}}}}}"]),k=n(27812),N=n(14998),T=v.ZP.div.withConfig({displayName:"MdViewSettingstyled__Container",componentId:"sc-1a8gan4-0"})(["padding:20px;@media all and (max-width:992px){padding:20px 10px;}"]),C=v.ZP.div.withConfig({displayName:"MdViewSettingstyled__Wrapper",componentId:"sc-1a8gan4-1"})(["border:none;background:#fff;border-radius:15px;box-shadow:var(--defaultBoxShadow3);padding:20px;.mainLayout{display:flex;justify-content:space-between;align-items:center;gap:20px;@media all and (max-width:992px){flex-direction:column;}.mainLayout__contentLayout{flex:1;width:100%;.mainLayout__cententLayout__titleBox{display:flex;flex-direction:row;align-items:center;justify-content:space-between;.mainLayout__cententLayout__titleBox__title{font-size:18px;font-weight:600;}.mainLayout__cententLayout__titleBox__buttonGroup{display:flex;flex-direction:row;align-items:center;gap:5px;.mainLayout__cententLayout__titleBox__buttonGroup__iconButton{width:20px;height:20px;border-radius:5px;}}}.mainLayout__contentLayout__contentList{margin-top:10px;border:1px solid #e0e0e0;border-radius:10px;display:flex;flex-direction:column;height:300px;overflow:auto;}.mainLayout__contentLayout__contentList__item{padding:10px;font-size:14px;cursor:pointer;&:hover{background:#f0f0f0;}}.mainLayout__contentLayout__contentList__item-isSelected{font-weight:700;color:var(--mainColor);}}.mainLayout__exchangeButton{width:48px;height:48px;border-radius:10px;padding:5px;box-shadow:var(--defaultBoxShadow);border-color:var(--mainColor);}}.footerButtonGroupLayout{margin-top:20px;display:flex;justify-content:flex-end;gap:10px;button{width:150px;border-radius:10px;background-color:var(--defaultModalCloseColor);color:#fff;font-weight:700;border:none;}.footerButtonGroupLayout__confirmButton{background-color:var(--mainColor);}}"]),E=n(24719);function S(e){var t,n=e.open,o=e.onClose,r=e.excelTranslatorList,i=e.bookmarkExcelTranslatorIdList,a=e.onConfirm,d=(0,l.useState)([]),s=d[0],u=d[1],c=(0,l.useState)(null),p=c[0],x=c[1];(0,l.useEffect)((function(){i&&u(i)}),[i]);var v=function(e){x(e)};return(0,_.jsx)(_.Fragment,{children:(0,_.jsxs)(N.Q,{open:n,onClose:function(){return o()},maxWidth:"md",children:[(0,_.jsx)(N.Q.CloseButton,{onClose:function(){return o()}}),(0,_.jsx)(N.Q.Title,{children:"\ubcc0\ud658\uae30 \uc990\uaca8\ucc3e\uae30 \uc124\uc815"}),(0,_.jsx)(T,{children:(0,_.jsxs)(C,{children:[(0,_.jsxs)("div",{className:"mainLayout",children:[(0,_.jsxs)("div",{className:"mainLayout__contentLayout",children:[(0,_.jsxs)("div",{className:"mainLayout__cententLayout__titleBox",children:[(0,_.jsx)("div",{className:"mainLayout__cententLayout__titleBox__title",children:"\uc990\uaca8\ucc3e\uae30"}),(0,_.jsxs)("div",{className:"mainLayout__cententLayout__titleBox__buttonGroup",children:[(0,_.jsx)(L.Z,{type:"button",className:"mainLayout__cententLayout__titleBox__buttonGroup__iconButton",onClick:function(){return function(){if(null!==s&&void 0!==s&&s.find((function(e){return e===(null===p||void 0===p?void 0:p.id)}))){var e=s.indexOf(null===p||void 0===p?void 0:p.id);if(e<0||e>=(null===s||void 0===s?void 0:s.length)-1)return;var t=E.Z.reorder(s,e,e+1);u(t)}}()},children:(0,_.jsx)(f.Z,{src:"/images/icon/arrow_downward_000000.svg"})}),(0,_.jsx)(L.Z,{type:"button",className:"mainLayout__cententLayout__titleBox__buttonGroup__iconButton",onClick:function(){return function(){if(null!==s&&void 0!==s&&s.find((function(e){return e===(null===p||void 0===p?void 0:p.id)}))){var e=s.indexOf(null===p||void 0===p?void 0:p.id);if(e<=0)return;var t=E.Z.reorder(s,e,e-1);u(t)}}()},children:(0,_.jsx)(f.Z,{src:"/images/icon/arrow_upward_000000.svg"})})]})]}),(0,_.jsx)("div",{className:"mainLayout__contentLayout__contentList",children:null===s||void 0===s?void 0:s.map((function(e){var t=null===r||void 0===r?void 0:r.find((function(t){return t.id===e}));return t?(0,_.jsx)("div",{className:"mainLayout__contentLayout__contentList__item ".concat((null===p||void 0===p?void 0:p.id)===(null===t||void 0===t?void 0:t.id)?"mainLayout__contentLayout__contentList__item-isSelected":""),onClick:function(){return v(t)},children:null===t||void 0===t?void 0:t.name},null===t||void 0===t?void 0:t.id):null}))})]}),(0,_.jsx)(L.Z,{className:"mainLayout__exchangeButton",onClick:function(){null!==s&&void 0!==s&&s.includes(null===p||void 0===p?void 0:p.id)?u((function(e){return null===e||void 0===e?void 0:e.filter((function(e){return e!==(null===p||void 0===p?void 0:p.id)}))})):u((function(e){return null===e||void 0===e?void 0:e.concat(null===p||void 0===p?void 0:p.id)}))},children:(0,_.jsx)(f.Z,{src:"/images/icon/exchange_default_808080.svg"})}),(0,_.jsxs)("div",{className:"mainLayout__contentLayout",children:[(0,_.jsx)("div",{className:"mainLayout__cententLayout__titleBox",children:(0,_.jsx)("div",{className:"mainLayout__cententLayout__titleBox__title",children:"\uc77c\ubc18"})}),(0,_.jsx)("div",{className:"mainLayout__contentLayout__contentList",children:null===r||void 0===r||null===(t=r.filter((function(e){return!(null!==s&&void 0!==s&&s.includes(null===e||void 0===e?void 0:e.id))})))||void 0===t?void 0:t.map((function(e){return(0,_.jsx)("div",{className:"mainLayout__contentLayout__contentList__item ".concat((null===p||void 0===p?void 0:p.id)===(null===e||void 0===e?void 0:e.id)?"mainLayout__contentLayout__contentList__item-isSelected":""),onClick:function(){return v(e)},children:null===e||void 0===e?void 0:e.name},null===e||void 0===e?void 0:e.id)}))})]})]}),(0,_.jsxs)("div",{className:"footerButtonGroupLayout",children:[(0,_.jsx)(L.Z,{type:"button",onClick:function(){return o()},children:"\ucde8\uc18c"}),(0,_.jsx)(L.Z,{type:"button",className:"footerButtonGroupLayout__confirmButton",onClick:function(){return function(){var e=(0,k.Z)(new Set(null===s||void 0===s?void 0:s.filter((function(e){return null===r||void 0===r?void 0:r.some((function(t){return t.id===e}))}))));a(e),o()}()},children:"\ud655\uc778"})]})]})})]})})}var B=n(10620);function H(e){var t,n,o=e.excelTranslatorList,r=e.selectedExcelTranslator,i=e.onSetSelectedExcelTranslator,a=(0,d.useRouter)(),s=(0,B.G)(),u=(0,l.useState)(!1),c=u[0],p=u[1],x=function(e){p(e)};return(0,_.jsxs)(_.Fragment,{children:[(0,_.jsx)(D,{children:(0,_.jsxs)("div",{className:"wrapper",children:[(0,_.jsx)("div",{className:"wrapper__title",children:"\ubcc0\ud658\uae30 \uc120\ud0dd"}),(0,_.jsxs)("div",{className:"wrapper__body",children:[(0,_.jsxs)(j.Z,{value:null===r||void 0===r?void 0:r.id,onChange:function(e){return function(e){var t=e.target.value,n=null===o||void 0===o?void 0:o.find((function(e){return e.id===t}));i(n||null)}(e)},children:[(0,_.jsx)("option",{value:"",children:"\ubcc0\ud658\uae30\ub97c \uc120\ud0dd\ud558\uc138\uc694."}),(0,_.jsx)("option",{disabled:!0,children:"===== \uc990\uaca8\ucc3e\uae30 ====="}),null===s||void 0===s||null===(t=s.bookmarkExcelTranslatorIdListForTranslator)||void 0===t?void 0:t.map((function(e){var t=null===o||void 0===o?void 0:o.find((function(t){return t.id===e}));return t?(0,_.jsx)("option",{value:null===t||void 0===t?void 0:t.id,children:null===t||void 0===t?void 0:t.name},e):null})),(0,_.jsx)("option",{disabled:!0,children:"===== \uc77c\ubc18 ====="}),null===o||void 0===o||null===(n=o.filter((function(e){var t;return!(null!==s&&void 0!==s&&null!==(t=s.bookmarkExcelTranslatorIdListForTranslator)&&void 0!==t&&t.includes(null===e||void 0===e?void 0:e.id))})))||void 0===n?void 0:n.map((function(e){return(0,_.jsx)("option",{value:null===e||void 0===e?void 0:e.id,children:null===e||void 0===e?void 0:e.name},null===e||void 0===e?void 0:e.id)}))]}),(0,_.jsxs)("div",{className:"wrapper__body__buttonGroup",children:[(0,_.jsxs)(L.Z,{type:"button",className:"wrapper__body__buttonGroup__textButton",onClick:function(){return x(!0)},children:["\ubcf4\uae30",(0,_.jsx)("br",{}),"\uc124\uc815"]}),r&&(0,_.jsx)(L.Z,{type:"button",className:"wrapper__body__buttonGroup__iconButton",onClick:function(){null===a||void 0===a||a.push({pathname:"/excel-editor/translator/setting",query:{excelTranslatorId:null===r||void 0===r?void 0:r.id}})},children:(0,_.jsx)("div",{className:"iconFigure",children:(0,_.jsx)(f.Z,{src:"/images/icon/settings_default_000000.svg"})})})]})]})]})}),c&&(0,_.jsx)(S,{open:c,onClose:function(){return x(!1)},excelTranslatorList:o,bookmarkExcelTranslatorIdList:null===s||void 0===s?void 0:s.bookmarkExcelTranslatorIdListForTranslator,onConfirm:function(e){null===s||void 0===s||s._onSetBookmarkExcelTranslatorIdListForTranslator(e)}})]})}var Y=n(16835),Z=n(63893),R=v.ZP.div.withConfig({displayName:"FdUploaderstyled__Container",componentId:"sc-jh1scx-0"})(["margin-top:20px;padding:0 20px;@media all and (max-width:992px){padding:0 10px;}> .wrapper{padding:20px;background:#fff;border:none;border-radius:15px;box-shadow:var(--defaultBoxShadow3);display:flex;flex-direction:row;gap:10px;> .wrapper__textButton{width:200px;border:1px solid #00000000;background:var(--grayButtonColor);border-radius:10px;font-weight:700;font-size:14px;color:#000;&:hover{background-color:var(--grayButtonHoverColor);}}> .wrapper__refreshButton{width:150px;border:1px solid #00000000;background:var(--grayButtonColor);border-radius:10px;font-weight:700;font-size:14px;color:#000;&:hover{background-color:var(--grayButtonHoverColor);}}>.wrapper__downloadButton{width:250px;border:1px solid #00000000;background:var(--mainColor);border-radius:10px;font-weight:700;font-size:14px;color:#fff;}}"]),M=n(81728);function z(e){var t=e.uploadHeaderList,n=e.downloadHeaderList,o=e.onReqUploadExcel,r=e.onReqDownloadExcel,i=e.onRefresh,a=(0,l.useState)(!1),d=a[0],s=a[1],u=(0,M.Z)(),c=(0,Y.Z)(u,2),p=c[0],f=c[1],x=function(e){s(e)};return(0,_.jsxs)(_.Fragment,{children:[(0,_.jsx)(R,{children:(0,_.jsxs)("div",{className:"wrapper",children:[(!t||!n)&&(0,_.jsx)(L.Z,{type:"button",className:"wrapper__textButton",onClick:function(){return x(!0)},children:"\ubcc0\ud658\ud560 \uc5d1\uc140 \uc120\ud0dd"}),(t||n)&&(0,_.jsxs)(_.Fragment,{children:[(0,_.jsx)(L.Z,{type:"button",className:"wrapper__refreshButton",onClick:function(){i()},children:"\ucd08\uae30\ud654"}),(0,_.jsx)(L.Z,{type:"button",className:"wrapper__downloadButton",onClick:function(){return f(!0),void r()},disabled:p,children:"\uc5d1\uc140 \ubcc0\ud658\ud558\uae30"})]})]})}),d&&(0,_.jsx)(Z.Z,{open:d,onClose:function(){return x(!1)},onConfirm:function(e){return function(e){var t=e.formData;o(t,(function(){return x(!1)}))}({formData:e.formData})}})]})}var O=n(17362),I=n(72835),F=n(12589),P=O.T.baseExcelEditorPage(),U=(0,F.E)();var G=n(18625),W=(0,n(41778).v)();function q(e){var t,n=(0,s.v9)((function(e){return e.workspaceRedux})),r=null===n||void 0===n||null===(t=n.workspaceInfo)||void 0===t?void 0:t.id,a={reqFetchExcelTranslatorList:function(){var e=(0,o.Z)(i().mark((function e(t){var n,o,r,a=arguments;return i().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return n=t.params,o=t.headers,r=a.length>1&&void 0!==a[1]?a[1]:function(e,t){},e.next=4,P.searchList({params:n,headers:o}).then((function(e){var t;200===(null===e||void 0===e?void 0:e.status)&&r(null===e||void 0===e||null===(t=e.data)||void 0===t?void 0:t.data,e)})).catch((function(e){I.e.error(e)}));case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),reqUploadSourceExcel:function(){var e=(0,o.Z)(i().mark((function e(t){var n,o,r,a,d=arguments;return i().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return n=t.params,o=t.body,r=t.headers,a=d.length>1&&void 0!==d[1]?d[1]:function(e,t){},e.next=4,P.uploadSourceExcel({params:n,body:o,headers:r}).then((function(e){var t;200===(null===e||void 0===e?void 0:e.status)&&a(null===e||void 0===e||null===(t=e.data)||void 0===t?void 0:t.data,e)})).catch((function(e){I.e.error(e)}));case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),reqDownloadResultExcel:function(){var e=(0,o.Z)(i().mark((function e(t){var n,o,r,a,d=arguments;return i().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return n=t.params,o=t.body,r=t.headers,a=d.length>1&&void 0!==d[1]?d[1]:function(e,t){},e.next=4,P.downloadResultExcel({params:n,body:o,headers:r}).then((function(e){if(200===e.status){var t=new Blob([e.data],{type:"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"}),n=document.createElement("a"),r=URL.createObjectURL(t),i=U.dateToYYYYMMDDhhmmssFile(new Date);n.href=r,n.download="".concat(i,"_").concat(null===o||void 0===o?void 0:o.excelTranslatorName,".xlsx"),n.click(),URL.revokeObjectURL(r),a("success")}})).catch((function(e){I.e.error(e)}));case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()},d=W.useFetchList({headers:{wsId:r}}),u=function(e){var t=(0,l.useState)(null),n=t[0],o=t[1],r=(0,l.useState)(null),i=r[0],a=r[1],d=(0,l.useState)(null),s=d[0],u=d[1],c=(0,l.useState)(null),p=c[0],f=c[1],x=(0,l.useState)(null),v=x[0],h=x[1],m=(0,l.useState)(null),w=m[0],_=m[1],g=function(e){u(e)},b=function(e){f(e)},y=function(e){h(e)},L=function(e){_(e)};return{excelTranslatorList:n,selectedExcelTranslator:i,uploadHeaderList:s,uploadRowDataList:p,downloadHeaderList:v,downloadRowDataList:w,onSetExcelTranslatorList:function(e){o(e)},onSetSelectedExcelTranslator:function(e){a(e),g(null),b(null),y(null),L(null)},onSetUploadHeaderList:g,onSetUploadRowDataList:b,onSetDownloadHeaderList:y,onSetDownloadRowDataList:L}}();(0,l.useEffect)((function(){var e,t;null!==d&&void 0!==d&&d.data&&(null===u||void 0===u||u.onSetExcelTranslatorList(null===d||void 0===d||null===(e=d.data)||void 0===e||null===(t=e.data)||void 0===t?void 0:t.data))}),[null===d||void 0===d?void 0:d.data]);var c=function(){var e=(0,o.Z)(i().mark((function e(t,n){var o;return i().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return(0,G.f5)().showBackdrop(),t.append("excelTranslatorId",null===u||void 0===u||null===(o=u.selectedExcelTranslator)||void 0===o?void 0:o.id),e.next=4,a.reqUploadSourceExcel({params:null,body:t,headers:{wsId:r}},(function(e,t){e&&(u.onSetUploadHeaderList(null===e||void 0===e?void 0:e.uploadHeaderList),u.onSetUploadRowDataList(null===e||void 0===e?void 0:e.uploadRowDataList),u.onSetDownloadHeaderList(null===e||void 0===e?void 0:e.downloadHeaderList),u.onSetDownloadRowDataList(null===e||void 0===e?void 0:e.downloadRowDataList),n())}));case 4:(0,G.f5)().hideBackdrop();case 5:case"end":return e.stop()}}),e)})));return function(t,n){return e.apply(this,arguments)}}(),f=function(){var e=(0,o.Z)(i().mark((function e(){var t,n;return i().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return n={excelTranslatorName:null===u||void 0===u||null===(t=u.selectedExcelTranslator)||void 0===t?void 0:t.name,downloadHeaderList:null===u||void 0===u?void 0:u.downloadHeaderList,downloadRowDataList:null===u||void 0===u?void 0:u.downloadRowDataList},(0,G.f5)().showBackdrop(),e.next=4,a.reqDownloadResultExcel({body:n,headers:{wsId:r}},(function(e,t){console.log("\ubcc0\ud658\uae30 \uacb0\uacfc \uc5d1\uc140 \ub2e4\uc6b4\ub85c\ub4dc \uc0c1\ud0dc: ".concat(e))}));case 4:(0,G.f5)().hideBackdrop();case 5:case"end":return e.stop()}}),e)})));return function(){return e.apply(this,arguments)}}();return d.isLoading?(0,G.f5)().showBackdrop():(0,G.f5)().hideBackdrop(),(0,_.jsx)(_.Fragment,{children:(0,_.jsxs)(p.Z,{sidebarColor:"#ffffff",sidebarName:"\uc5d1\uc140 \ud3b8\uc9d1\uae30",headerName:"\ub300\uc2dc\ubcf4\ub4dc",children:[(0,_.jsx)(H,{excelTranslatorList:null===u||void 0===u?void 0:u.excelTranslatorList,selectedExcelTranslator:null===u||void 0===u?void 0:u.selectedExcelTranslator,onSetSelectedExcelTranslator:u.onSetSelectedExcelTranslator}),(null===u||void 0===u?void 0:u.selectedExcelTranslator)&&(0,_.jsxs)(_.Fragment,{children:[(0,_.jsx)(z,{uploadHeaderList:null===u||void 0===u?void 0:u.uploadHeaderList,downloadHeaderList:null===u||void 0===u?void 0:u.downloadHeaderList,onReqUploadExcel:function(e,t){return c(e,t)},onReqDownloadExcel:function(){return f()},onRefresh:function(){u.onSetUploadHeaderList(null),u.onSetUploadRowDataList(null),u.onSetDownloadHeaderList(null),u.onSetDownloadRowDataList(null)}}),(0,_.jsx)(g,{selectedExcelTranslator:null===u||void 0===u?void 0:u.selectedExcelTranslator,uploadHeaderList:null===u||void 0===u?void 0:u.uploadHeaderList,uploadRowDataList:null===u||void 0===u?void 0:u.uploadRowDataList,downloadHeaderList:null===u||void 0===u?void 0:u.downloadHeaderList,downloadRowDataList:null===u||void 0===u?void 0:u.downloadRowDataList})]})]})})}function V(e){var t=(0,d.useRouter)(),n=(0,s.v9)((function(e){return e.userRedux})),r=(0,l.useState)(!0),p=r[0],f=r[1];return(0,l.useEffect)((function(){null!==t&&void 0!==t&&t.isReady&&f(!1)}),[null===t||void 0===t?void 0:t.isReady]),(0,l.useEffect)((function(){function e(){return(e=(0,o.Z)(i().mark((function e(){return i().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(!p){e.next=2;break}return e.abrupt("return");case 2:if(!1!==n.isLoading||n.userInfo){e.next=6;break}return alert("\ub85c\uadf8\uc778\uc774 \ud544\uc694\ud55c \uc11c\ube44\uc2a4 \uc785\ub2c8\ub2e4."),t.replace("/"),e.abrupt("return");case 6:case"end":return e.stop()}}),e)})))).apply(this,arguments)}!function(){e.apply(this,arguments)}()}),[p,t,n.isLoading,n.userInfo]),n.isLoading||!n.userInfo?null:(0,_.jsxs)(_.Fragment,{children:[(0,_.jsx)(a.default,{children:(0,_.jsx)("title",{children:"\uc5d1\uc140 \ubcc0\ud658\uae30 | \uc140\ub7ec\ud234 - \uc1fc\ud551\ubab0 \ud1b5\ud569\uad00\ub9ac"})}),(0,_.jsx)(u.Z,{}),(0,_.jsx)(c.Z,{}),(0,_.jsx)(q,{})]})}},12589:function(e,t,n){"use strict";n.d(t,{E:function(){return i}});var o=n(30381),r=n.n(o),i=function(){return{getRemainingDateCount:a,getStartDate:d,getEndDate:l,dateToYYMMDD:s,dateToYYYYMMDD:u,dateToYYYYMMDDhhmmss:c,dateToYYYYMMDDhhmmssWithT:p,dateToYYMMDDhhmmss:f,dateToYYYYMMDDhhmmssFile:x,getDiffDate:v,getDiffDays:h,dateToHHmm:m,getDiffHourWithUTC:w,setPlusDate:_,getCurrentUTCDateTime:g}};function a(e){var t=new Date,n=new Date(e);return Math.ceil((n-t)/864e5)}function d(e){var t=new Date(e);return t.setHours(0),t.setMinutes(0),t.setSeconds(0),t.setMilliseconds(0),t}function l(e){var t=new Date(e);return t.setHours(23),t.setMinutes(59),t.setSeconds(59),t.setMilliseconds(0),t}function s(e){var t=new Date(e);return r()(t).format("YY.MM.DD")}function u(e,t){var n=new Date(e),o=t||"-";return r()(n).format("YYYY".concat(o,"MM").concat(o,"DD"))}function c(e){var t=new Date(e);return r()(t).format("YYYY-MM-DD HH:mm:ss")}function p(e){var t=new Date(e);return r()(t).format("YYYY-MM-DDTHH:mm:ss")}function f(e,t,n){var o=new Date(e),i=t||"/",a=n||":";return r()(o).format("YY".concat(i,"MM").concat(i,"DD HH").concat(a,"mm").concat(a,"ss"))}function x(e){var t=new Date(e);return r()(t).format("YYYYMMDDHHmmss")}function v(e,t){var n=new Date(e),o=new Date(t);n.setHours(0,0,0,0),o.setHours(0,0,0,0);var r=Math.abs(o-n);return Math.round(r/864e5)+1}function h(e,t){var n=r()(e).diff(t,"days");return Math.abs(n)}function m(e){var t=new Date(e);return r()(t).format("HH:mm")}function w(){return Math.abs((new Date).getTimezoneOffset()/60)}function _(e,t,n,o){var i=new Date(e);return i.setFullYear(i.getFullYear()+t),i.setMonth(i.getMonth()+n),i.setDate(i.getDate()+o),new Date(r()(i))}function g(){var e=new Date;return r()(e).utc().format("YYYY-MM-DDTHH:mm:ss[Z]")}},24719:function(e,t,n){"use strict";var o=n(16835),r={isEmptyValues:function(e){return void 0===e||null===e||"object"===typeof e&&0===Object.keys(e).length||"string"===typeof e&&0===e.trim().length},isEmptyNumbers:function(e){return void 0===e||null===e||isNaN(e)||"object"===typeof e&&0===Object.keys(e).length||"string"===typeof e&&0===e.trim().length},emptyCheckAndGet:function(e,t){return r.isEmptyValues(e)?t||"":e},reorder:function(e,t,n){var r=Array.from(e),i=r.splice(t,1),a=(0,o.Z)(i,1)[0];return r.splice(n,0,a),r}};t.Z=r},40539:function(e,t,n){(window.__NEXT_P=window.__NEXT_P||[]).push(["/excel-editor/translator",function(){return n(66312)}])}},function(e){e.O(0,[3662,4885,8915,8983,8472,2032,7447,1401,9864,9774,2888,179],(function(){return t=40539,e(e.s=t);var t}));var t=e.O();_N_E=t}]);