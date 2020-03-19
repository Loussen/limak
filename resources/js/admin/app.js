import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App.vue'


import Welcome from './welcome/WelcomeComponent.vue'
import Orders from './orders/OrdersIndexComponent.vue'
import AllOrders from './orders/AllOrdersComponent.vue'
import AllOrders2 from './orders/AllOrdersComponent2.vue'
import IncomingOrders from './orders/IncominOrdersComponent.vue'
import ExecutingOrders from './orders/ExecutingOrdersComponent.vue'
import WaitingOrders from './orders/WaitingOrdersComponent.vue'
import ExecutingOrderDetails from './orders/ExecutingOrderDetailsComponent.vue'
import NewExecutingOrderDetails from './orders/NewExecutingOrderDetailsComponent.vue'
import NewExecutingOrderDetails5 from './orders/NewExecutingOrderDetails5Component.vue'
import NewExecutingOrderDetails2 from './orders/NewExecutingOrderDetails2Component.vue'
import NotHavingInvoice from './orders/NotHavingInvoiceComponent.vue'
import Invoice from './orders/InvoiceComponent.vue'
import CompletedOrders from './orders/CompletedOrdersComponent'
import LateOrders from './orders/LateOrdersComponent'
import Cash from './account/CashComponent'
import KassaDashboard from './account/KassaDashboardComponent'
import Kassa from './account/KassaComponent'
import CashCourier from './account/CashCourierComponent'
import CashTry from './account/CashTryComponent'
import AccountLogs from './account/AccountLogsComponent'
import Accounts from './account/AccountComponent'
import DepotDashboard from './depot/DepotDashboardComponent'
import Depot from './depot/DepotComponent'
import DepotDelivered from './depot/DepotDeliveredComponent'
import Depot15Days from './depot/Depot15DaysComponent'
import Depot45Days from './depot/Depot45DaysComponent'
import DepotEditor from './depot/DepotEditorComponent'
import DepotPayed from './depot/DepotPayedComponent'
import DepotCustom from './depot/DepotCustomComponent'
import DepotRegion from './depot/DepotRegionComponent'
import UserManagement from './users/UsersIndexComponent'
import UsersList from './users/UsersListComponent'
import UserInfo from './users/UsersAllInfoComponent'
import UserInfo2 from './users/UsersAllInfo2Component'

import Courier from './courier/CourierIndexComponent';
import CourierPanel from './courier/CourierPanelComponent';

import Kuryer from './kuryer/KuryerIndexComponent';
import KuryerPanel from './kuryer/KuryerPanelComponent';
import KuryerCompleted from './kuryer/KuryerCompletedComponent';


import Transfer from './transfers/TransferIndexComponent';


import Accountant from './accountant/AccountComponent'
import AccountantAccountLogs from './accountant/AccountLogsComponent'
import BackToCard from './accountant/BackToCardComponent'
import BackToCardDetails from './accountant/BackToCardDetailsComponent'
import CustomerBalance from './accountant/CustomerBalanceComponent'
import LogBalances from './accountant/LogBalancesComponent'
import Dispatch from './accountant/DispatchComponent'
import EFT from './accountant/EftComponent'
import CashBack from './accountant/CashBackComponent'
import Expenses from './accountant/ExpensesComponent'
import Expenses2 from './accountant/Expenses2Component'
import ExpenseDetails from './accountant/ExpenseDetailsComponent'

import Lost from './problems/LostComponent'
import Complaints from './problems/ComplaintsComponent'
import FormalComplaints from './problems/FormalComplaintsComponent'
import showComplaint from './problems/showComplaintComponent'
import Solving from './problems/solvingComponent'

import StaticPages from  './static-pages/StaticPages.vue'
import StaticPagesShow from  './static-pages/StaticPagesShow.vue'
import StaticPagesGenerator from  './static-pages/StaticPagesGenerator.vue'

import Custom from './custom/CustomComponent'
import Prices from './custom/PricesComponent'
import PricesUsa from './custom/PricesUsaComponent'

import OrdersStatistic from './statistics/OrdersStatisticComponent'

import UserManagementAPI from './user-management/UserManagementComponent'

import Cash2 from './account/Cash2Component'

import Statistics from './statistics/StatisticsComponent'

import moment from 'moment'

const routes = [
    { path: '/accountant', component: Accountant },
    { path: '/accountant/account/logs/:id', component: AccountantAccountLogs },
    { path: '/accountant/backToCard', component: BackToCard },
    { path: '/accountant/backToCard/:id', component: BackToCardDetails },
    { path: '/accountant/customerBalance', component: CustomerBalance },
    { path: '/accountant/logBalances', component: LogBalances },
    { path: '/accountant/eft', component: EFT },
    { path: '/accountant/cashBack', component: CashBack},
    { path: '/accountant/expenses', component: Expenses},
    { path: '/accountant/expenses2', component: Expenses2},
    { path: '/accountant/dispatch', component: Dispatch},
    { path: '/accountant/expenseDetails/:id', component: ExpenseDetails},
    { path: '/', component: Welcome, props: true},
    { path: '/orders', component: Orders },
    { path: '/orders/all', component: AllOrders },
    { path: '/orders/all2', component: AllOrders2 },
    { path: '/orders/incoming', component: IncomingOrders },
    { path: '/orders/executing', component: ExecutingOrders},
    { path: '/orders/waiting', component: WaitingOrders},
    { path: '/orders/executing/:id', component:ExecutingOrderDetails },
    { path: '/orders/executing/:id/:region', component:NewExecutingOrderDetails },
    { path: '/orders/executing5/:id/:region/:client_id', component:NewExecutingOrderDetails },
    { path: '/orders/executing/:id/:region/:client_id', component:NewExecutingOrderDetails5 },
    { path: '/orders/executing2/:id/:region/:client_id', component:NewExecutingOrderDetails2 },
    { path: '/orders/noinvoice', component: NotHavingInvoice },
    { path: '/orders/completed', component: CompletedOrders },
    { path: '/orders/late', component: LateOrders },
    { path: '/orders/invoice/:id', component: Invoice },
    { path: '/accounts', component: Accounts },
    //{ path: '/account/cash', component: Cash },
    { path: '/account/kassa', component: KassaDashboard },
    { path: '/account/kassa/:id', component: Kassa },
    { path: '/account/cashCourier', component: CashCourier },
    { path: '/account/cashTry', component: CashTry },
    //{ path: '/account/cash2', component: Cash2 },
    { path: '/account/logs/:id', component: AccountLogs },
    { path: '/depot', component: DepotDashboard },
    { path: '/depot/editor', component: DepotEditor },
    { path: '/depot/payed', component: DepotPayed },
    { path: '/depot/delivered', component: DepotDelivered },
    { path: '/depot/15days', component: Depot15Days },
    { path: '/depot/45days', component: Depot45Days },
    { path: '/depot/custom', component: DepotCustom },

    { path: '/depot/:id', component: Depot },
    { path: '/depot/:id/editor', component: DepotEditor },
    { path: '/depot/:id/payed', component: DepotPayed },
    { path: '/depot/:id/delivered', component: DepotDelivered },
    { path: '/depot/:id/15days', component: Depot15Days },
    { path: '/depot/:id/45days', component: Depot45Days },
    { path: '/depot/:id/custom', component: DepotCustom },
    { path: '/depot/:id/region', component: DepotRegion },

    { path: '/transfer', component: Transfer },
    { path: '/courier', component: Kuryer },
    { path: '/kuryer', component: Kuryer },
    { path: '/kuryer-panel', component: KuryerPanel },
    { path: '/kuryer-completed', component: KuryerCompleted },
    { path: '/courier-panel', component: CourierPanel },

    { path: '/accountant', component: Courier },
    { path: '/news', component: Courier },
    { path: '/statistics', component: Statistics },
    { path: '/shops', component: Courier },
    { path: '/customs', component: Custom },
    { path: '/customs/prices', component: Prices },
    { path: '/customs/pricesUsa', component: PricesUsa },
    { path: '/userManagement', component: UserManagement },
    { path: '/users/list', component: UsersList },
    { path: '/users/info', component: UserInfo },
    { path: '/users/info2', component: UserInfo2 },
    { path: '/problems/lost', component: Lost },
    { path: '/problems/complaints', component: Complaints },
    { path: '/problems/formal-complaints', component: FormalComplaints },
    { path: '/problems/show-complaint/:id', component: showComplaint },
    { path: '/ordersStatistic/', component: OrdersStatistic },
    { path: '/staticPage/', component: StaticPages },
    { path: '/staticPage/:id', component: StaticPagesShow },
    { path: '/staticPage/:id/generator', component: StaticPagesGenerator },



    { path: '/user-management', component: UserManagementAPI },
    { path: '/problems/solving/:id', component: Solving },

]


export const router = new VueRouter({
    routes 
})


Vue.use(VueRouter)

Vue.component('app', App)

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD/MM/YYYY HH:mm')
    }
})
const app = new Vue({
    router,
    el: '#admin-index'
})