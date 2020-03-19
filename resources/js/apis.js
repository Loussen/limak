const nodeAPI = 'https://api.limak.az/';
export const api = {
    spList: '/admin/static-pages/list',
    spPost: '/admin/static-pages/insert',
    spPut: '/admin/static-pages/update',
    spShow: '/admin/static-pages/show/',
    gList: '/admin/generator/list/',
    gInsert: '/admin/generator/insert',
    gUpdateText: '/admin/generator/update-text',
    gUpdateVideo: '/admin/generator/update-video',
    gUpdateImage: '/admin/generator/update-image',
    ctList: '/admin/content-types/list',

    // Messenger
    msgList: '/admin/messages/list',
    msgShow: '/admin/messages/show/',
    msgPost: '/admin/messages/post',

    msgerList: '/messenger/list',
    msgerPost: '/messenger',
    msgerReply: '/messenger/reply',
    msgerCategories: '/messenger/categories',
    msgerClose: '/messenger/close/',

    // Notification - Center
    nodeAPI: 'https://api.limak.az/',
    ncNew: nodeAPI + 'new-message',
    ncSubscribe: nodeAPI + 'subscribe-conversation',
    ncNewList: nodeAPI + 'new-messages/list/',
    ncUsrMsg: nodeAPI + 'new-messages/user/',
    chatById: nodeAPI + 'chat/',

    // Url parser
    parseUrl: '/order/parse',

    // Orders
    orderLinkInsert: '/order/link/insert',
    orderBalanceLinkInsert: '/order/link/insertBalance',
    orderLinkInsert2: '/order/link/insert2',
    orderLinkData: '/order/link/getData',

    // User
    admin: '/admin/admin-user',
    currency: '/api/v1/currency',

    invoiceCashierList: '/admin/depot/cashier/list',
    invoiceCashierPay: '/admin/depot/cashier/pay',
    invoiceDepotPayed: '/admin/depot/payed/list',
    invoiceDepotFinish: '/admin/depot/payed/finish', //new
    invoiceDepotEditorInsert: '/admin/depot/editor/insert',
    invoiceDepotEditorUpdate: '/admin/depot/editor/update',
    invoiceDepotEditorDelete: '/admin/depot/editor/delete',
    invoiceDepotEditorList: '/admin/depot/editor/list',
    invoiceDepotList: '/admin/depot/list',
    invoiceDepotInsert: '/admin/depot/store/insert',
    invoiceDepotRole: '/admin/depot/check/role',
}
