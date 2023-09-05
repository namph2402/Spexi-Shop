export class StorageUtil {

  static get(key: string): string {
    return window.localStorage.getItem(key);
  }

  static set(key: string, value: string): void {
    window.localStorage.setItem(key, value);
  }

  static delete(key: string): void {
    window.localStorage.removeItem(key);
  }

  static clear(): void {
    window.localStorage.clear();
  }

  static setUser(user: any) {
    StorageUtil.set('name', user['name']);
    StorageUtil.set('username', user['username']);
    StorageUtil.set('avatar', user['avatar']);
    StorageUtil.set('token', user['token']);
  }

  static getUser() {
    let user: any = {};
    user.name = StorageUtil.get('name');
    user.username = StorageUtil.get('username');
    user.avatar = StorageUtil.get('avatar');
    user.token = StorageUtil.get('token');
    return user;
  }


}
