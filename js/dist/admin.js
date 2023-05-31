(()=>{var e={n:t=>{var r=t&&t.__esModule?()=>t.default:()=>t;return e.d(r,{a:r}),r},d:(t,r)=>{for(var n in r)e.o(r,n)&&!e.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:r[n]})},o:(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r:e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}},t={};(()=>{"use strict";function r(e,t){return r=Object.setPrototypeOf?Object.setPrototypeOf.bind():function(e,t){return e.__proto__=t,e},r(e,t)}e.r(t);const n=flarum.core.compat["admin/app"];var a=e.n(n);const o=flarum.core.compat["admin/components/DashboardWidget"];var l=e.n(o);const i=flarum.core.compat["common/components/Alert"];var s=e.n(i),c=function(e){var t,n;function o(){return e.apply(this,arguments)||this}n=e,(t=o).prototype=Object.create(n.prototype),t.prototype.constructor=t,r(t,n);var l=o.prototype;return l.className=function(){return"NearataCloudflare DevelopmentWarningWidget"},l.content=function(){var e=a().translator.trans("nearata-cloudflare.admin.development_mode_widget.title");return m(s(),{title:e,type:"warning",dismissible:!1,icon:"fas fa-exclamation-triangle"})},o}(l());const u=flarum.core.compat["admin/components/DashboardPage"];var p=e.n(u);const f=flarum.core.compat["common/components/Button"];var d=e.n(f);const g=flarum.core.compat["common/components/Link"];var y=e.n(g);const _=flarum.core.compat["common/extend"],h=flarum.core.compat["common/utils/extractText"];var v=e.n(h),b=function(e,t){return void 0===t&&(t={}),a().translator.trans("nearata-cloudflare.admin.settings."+e,t)};a().initializers.add("nearata-cloudflare",(function(){(0,_.extend)(p().prototype,"availableWidgets",(function(e){"1"===a().data.settings["nearata-cloudflare.development-mode"]&&e.add("nearataCloudflareDevelopment",m(c,null),100)})),a().extensionData.for("nearata-cloudflare").registerSetting({setting:"nearata-cloudflare.api-key",type:"password",label:b("api_key"),help:b("api_key_help",{url:m(y(),{external:!0,href:"https://developers.cloudflare.com/fundamentals/api/get-started/create-token"})})}).registerSetting({setting:"nearata-cloudflare.security-level",type:"select",label:b("security_level_label"),options:{off:b("security_level_options.off"),essentially_off:b("security_level_options.essentially_off"),low:b("security_level_options.low"),medium:b("security_level_options.medium"),high:b("security_level_options.high"),under_attack:b("security_level_options.under_attack")},help:b("refer_to",{url:m(y(),{external:!0,href:"https://developers.cloudflare.com/support/firewall/settings/understanding-the-cloudflare-security-level"})})}).registerSetting((function(){return m("div",{class:"Form-group"},m("h2",null,b("minify_setting.section_title")),m("div",{class:"helpText"},b("refer_to",{url:m(y(),{external:!0,href:"https://developers.cloudflare.com/support/speed/optimization-file-size/using-cloudflare-auto-minify/"})})))})).registerSetting({setting:"nearata-cloudflare.minify-css",type:"checkbox",label:b("minify_setting.css"),help:b("minify_setting.css_help")}).registerSetting({setting:"nearata-cloudflare.minify-html",type:"checkbox",label:b("minify_setting.html"),help:b("minify_setting.html_help")}).registerSetting({setting:"nearata-cloudflare.minify-js",type:"checkbox",label:b("minify_setting.js"),help:b("minify_setting.js_help")}).registerSetting({setting:"nearata-cloudflare.development-mode",type:"checkbox",label:"Development Mode",help:b("refer_to",{url:m(y(),{external:!0,href:"https://developers.cloudflare.com/api/operations/zone-settings-change-development-mode-setting"})})}).registerSetting((function(){var e=this;return m("div",{class:"Form-group"},d().component({className:"Button Button--danger",loading:this.loading,icon:this.success&&"fas fa-check",onclick:function(){if(confirm(v()(b("confirm_text")))){e.loading=!0,e.success=!1;var t=a().forum.attribute("apiUrl")+"/nearata/cloudflare/refreshZone";a().request({url:t,method:"PATCH"}).then((function(){e.success=!0})).catch((function(){e.success=!1})).finally((function(){e.loading=!1,m.redraw()}))}}},b("refresh_zone_button_label")),m("div",{class:"helpText"},b("refresh_zone_help")))})).registerSetting((function(){return m("h2",null,b("r2.section_title"))})).registerSetting({setting:"nearata-cloudflare.r2-bucket-name",type:"text",label:b("r2.bucket_name")}).registerSetting({setting:"nearata-cloudflare.r2-access-key-id",type:"password",label:b("r2.access_key_id")}).registerSetting({setting:"nearata-cloudflare.r2-access-key-secret",type:"password",label:b("r2.access_key_secret")}).registerSetting({setting:"nearata-cloudflare.r2-public-domain",type:"text",label:b("r2.public_domain")}).registerSetting({setting:"nearata-cloudflare.r2-s3-api",type:"text",label:b("r2.s3_api")})}))})(),module.exports=t})();
//# sourceMappingURL=admin.js.map