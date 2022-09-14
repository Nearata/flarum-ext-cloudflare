import app from "flarum/admin/app";
import Button from "flarum/common/components/Button";

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
        .registerSetting(function () {
            return m(".Form-group", [
                m(
                    Button,
                    {
                        className: "Button",
                        loading: this.loading,
                        icon: this.success ? "fas fa-check" : "",
                        onclick: () => {
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
            ]);
        });
});
