// * Packages
import { HistoryIcon } from "lucide-react";
// * Other
import { NavListLinksProps } from "@/back/Components/Layout/Sidebar/AppSidebar";
import { SidebarGroupLink } from "@/back/Components/Layout/Sidebar/SidebarRegistry";
import { AuthProp } from "@/back/types/global";
import str from "@/hooks/use-string";
import { GetTranslation } from "@/lib/utils";

export default function ActivityLogs(auth: AuthProp): SidebarGroupLink {
  const modelName = "activity-logs";
  const transActivityLogs = GetTranslation(
    `laravel-activity-logs::models.classes.${modelName}`,
    {
      choice: 2,
    },
  );

  const link: NavListLinksProps = {
    title: str(transActivityLogs).ucFirst().value(),
    model: modelName,
    icon: HistoryIcon,
    show: auth.policies.activityLogs.viewAny,
  };

  return { link, group: "admin" };
}
