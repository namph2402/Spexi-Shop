import {UserMeta} from '../user/user.meta';

export class CustomerMeta {
  id: number;
  user_id: number;
  fullname: string;
  phone: number;
  avatar: string;
  dob: string;
  gender: boolean;
  address: string;
  province: string;
  district: string;
  ward: string;
  account: UserMeta;
}
