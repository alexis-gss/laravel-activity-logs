import { ModelPolicy, UserModel } from "@/back/types/global";

type ActivityLogModel = {
  policies: ModelPolicy;
  id: number;
  model_class: string;
  modifications: string | null;
  user: UserModel | null;
  user_id: number | null;
  is_anonymous: boolean;
  is_console: boolean;
  model_id: number;
  event: number;
  created_at: Date;
};
