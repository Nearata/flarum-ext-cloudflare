import app from "flarum/admin/app";
import Button from "flarum/common/components/Button";

app.initializers.add("nearata-cloudflare", () => {
    app.extensionData
        .for("nearata-cloudflare")
        .registerSetting({
            setting: "nearata-cloudflare.api-key",
            type: "password",
            label: app.translator.trans(
                "nearata-cloudflare.admin.settings.api_key"
            ),
            help: app.translator.trans(
                "nearata-cloudflare.admin.settings.api_key_help",
                { url: "https://developers.cloudflare.com/api/tokens/create" }
            ),
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
                    "Refresh Zone ID"
                ),
            ]);
        });
});
