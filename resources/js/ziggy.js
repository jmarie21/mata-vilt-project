const Ziggy = {"url":"http:\/\/localhost","port":null,"defaults":{},"routes":{"reports":{"uri":"reports","methods":["GET","HEAD"]},"show.invoice":{"uri":"invoice","methods":["GET","HEAD"]},"manage_users":{"uri":"manage_users","methods":["GET","HEAD"]},"users.update":{"uri":"manage_users\/{id}","methods":["PUT"],"parameters":["id"]},"users.delete":{"uri":"manage_users\/{id}","methods":["DELETE"],"parameters":["id"]},"users.add":{"uri":"manage_users","methods":["POST"]},"invoice.create":{"uri":"invoice","methods":["POST"]},"password.change":{"uri":"change-password","methods":["GET","HEAD"]},"changePassword.update":{"uri":"change-password","methods":["PUT"]},"dashboard":{"uri":"dashboard","methods":["GET","HEAD"]},"projects":{"uri":"projects","methods":["GET","HEAD"]},"profile":{"uri":"profile","methods":["GET","HEAD"]},"personalInfo.update":{"uri":"profile\/update","methods":["PUT"]},"update.password":{"uri":"profile\/update_password","methods":["PUT"]},"logout":{"uri":"logout","methods":["POST"]},"home":{"uri":"\/","methods":["GET","HEAD"]},"password.request":{"uri":"password\/reset","methods":["GET","HEAD"]},"password.email":{"uri":"password\/email","methods":["POST"]},"password.reset":{"uri":"password\/reset\/{token}","methods":["GET","HEAD"],"parameters":["token"]},"password.update":{"uri":"password\/reset","methods":["POST"]},"signup":{"uri":"signup","methods":["GET","HEAD"]},"login":{"uri":"login","methods":["GET","HEAD"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
