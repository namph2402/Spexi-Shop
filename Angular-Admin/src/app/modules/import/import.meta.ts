import { ImportDetailMeta } from "./import-detail.meta";

export class ImportMeta {
  id: number;
  name: string;
  creator_id: number;
  creator_name: string;
  description: string;
  created_at: string;
  details: ImportDetailMeta;
}
