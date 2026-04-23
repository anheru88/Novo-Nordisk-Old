<template>
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-bell"></i>
            <span class="badge badge-danger" v-if="countNotifications > 0">{{
                countNotifications
            }}</span>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <li v-for="notification in notifications">
                <form action="/notificationView" method="post">
                    <input
                        type="hidden"
                        name="id"
                        :value="notification['id']"
                    />
                    <input
                        type="hidden"
                        name="destiny_id"
                        :value="notification['destiny_id']"
                    />
                    <input
                        type="hidden"
                        name="url"
                        :value="notification['url']"
                    />
                    <input type="hidden" name="readed" :value="1" />
                    <button type="submit" class="btn btn-link">
                            <i class="fas fa-envelope mr-2"></i>
                            <span class="text-muted text-sm">
                                {{ notification["data"] }}
                            </span>
                            <span class="pull-right time-text">
                                <timeago
                                    :datetime="notification['created_at']"
                                    :auto-update="60"
                                ></timeago>
                            </span>
                    </button>
                </form>
            </li>
            <li class="all-notifications">
                <div v-if="notifications.length > 0">
                    <a
                        href="/notifications"
                        class="dropdown-item dropdown-footer"
                    >
                        <strong>Ver todas las notificaciones</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>
                <div v-else>Sin Notificaciones</div>
            </li>
        </ul>
    </li>
</template>
<script>
var csrf_token = $('meta[name="csrf-token"]').attr('content');
import VueTimeago from "vue-timeago";
Vue.use(VueTimeago, {
    name: "Timeago", // Component name, `Timeago` by default
    locale: "es", // Default locale
    // We use `date-fns` under the hood
    // So you can use all locales from it
    locales: {
        es: require("date-fns/locale/es")
    }
});
export default {
    props: ["user_id"],
    data() {
        return {
            id: [],
            destiny_id: [],
            readed: [],
            token   : csrf_token,
            notifications: [],
            countNotifications: [],
            usersId: []
        };
    },
    methods: {
        getPosts() {
            return axios
                .post(
                    "/notificationView",
                    {
                        id: this.id,
                        destiny_id: this.destiny_id,
                        readed: this.readed
                    },
                    {
                        headers: {
                            "Content-type": "application/json",
                            "X-CSRF-TOKEN": this.token
                        }
                    }
                )
                .then(response => {
                    console.log("id: " + this.id);
                    console.log("destiny_id: " + this.destiny_id);
                })
                .catch(error => {
                    console.log("error: " + error);
                });
        },
        reportPersistentData() {
            axios.get("/notify").then(res => {
                // console.log(res.data);
                this.notifications = res.data;
            });
        },
        reportPersistentDataAll() {
            axios.get("/notifycountall").then(res => {
                // console.log(res.data);
                this.countNotifications = res.data;
            });
        },
        pusherNotification() {  // temporal
           /* window.Echo.channel("novo-channel").listen(
                "OrderNotificationsEvent",
                notification => {
                    var res = false;
                    // console.log(notification.notification);
                    this.usersId = notification.notification.userId;
                    if (this.usersId.length > 0) {
                        for (var i = 0; i < this.usersId.length; i++) {
                            var name = this.usersId[i];
                            if (name == this.user_id) {
                                res = true;
                                break;
                            }
                        }
                        if (res) {
                            toastr.options = {
                                closeButton: false,
                                progressBar: false,
                                newestOnTop: true
                            };
                            toastr
                                .info("Tiene una nueva notificación")
                                .css("width", "auto");
                            this.notifications.unshift({
                                destiny_id:  notification.notification.userId,
                                data: notification.notification.description,
                                url: notification.notification.url,
                                created_at: new Date()
                            });
                        }
                    }
                }
            );*/
        }
    },
    mounted() {
        // this.dbNotification(),
        this.reportPersistentDataAll() ,this.reportPersistentData(), this.pusherNotification();
    }
};
</script>
