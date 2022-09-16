import app from "flarum/admin/app";
import Button from "flarum/common/components/Button";
import extractText from "flarum/common/utils/extractText";

const trans = (key: string, params = {}) => {
    return app.translator.trans(
        `nearata-cloudflare.admin.settings.${key}`,
        params
    );
};

app.initializers.add("nearata-cloudflare", () => {
    app.extensionData
        .for("nearata-cloudflare")
        .registerSetting({
            setting: "nearata-cloudflare.api-key",
            type: "password",
            label: trans("api_key"),
            help: trans("api_key_help", {
                url: "https://developers.cloudflare.com/api/tokens/create",
            }),
        })
        .registerSetting({
            setting: "nearata-cloudflare.security-level",
            type: "select",
            label: trans("security_level_label"),
            options: {
                off: trans("security_level_options.off"),
                essentially_off: trans(
                    "security_level_options.essentially_off"
                ),
                low: trans("security_level_options.low"),
                medium: trans("security_level_options.medium"),
                high: trans("security_level_options.high"),
                under_attack: trans("security_level_options.under_attack"),
            },
            help: trans("security_level_help", {
                url: "https://support.cloudflare.com/hc/en-us/articles/200170056",
            }),
        })
        .registerSetting(() => {
            return m("h2", trans("minify_setting.section_title"));
        })
        .registerSetting({
            setting: "nearata-cloudflare.minify-css",
            type: "checkbox",
            label: trans("minify_setting.css"),
            help: trans("minify_setting.css_help"),
        })
        .registerSetting({
            setting: "nearata-cloudflare.minify-html",
            type: "checkbox",
            label: trans("minify_setting.html"),
            help: trans("minify_setting.html_help"),
        })
        .registerSetting({
            setting: "nearata-cloudflare.minify-js",
            type: "checkbox",
            label: trans("minify_setting.js"),
            help: trans("minify_setting.js_help"),
        })
        .registerSetting(function () {
            return m(".Form-group", [
                m(
                    Button,
                    {
                        className: "Button Button--danger",
                        loading: this.loading,
                        icon: this.success ? "fas fa-check" : "",
                        onclick: () => {
                            if (!confirm(extractText(trans("confirm_text")))) {
                                return;
                            }

                            this.loading = true;
                            this.success = false;

                            app.request({
                                url: `${app.forum.attribute(
                                    "apiUrl"
                                )}/nearata/cloudflare/refreshZone`,
                                method: "PATCH",
                            })
                                .then(() => {
                                    this.loading = false;
                                    this.success = true;

                                    m.redraw();
                                })
                                .catch(() => {
                                    this.loading = false;
                                    this.success = false;

                                    m.redraw();
                                });
                        },
                    },
                    trans("refresh_zone_button_label")
                ),
                m(".helpText", trans("refresh_zone_help")),
            ]);
        });
});
