// * Packages
import { FileTextIcon, UserIcon } from "lucide-react";
import { route } from "ziggy-js";
// * Components shadcn
import { Badge } from "@/components/ui/badge";
// * Other
import AuthenticatedLayout from "@/back/Layouts/AuthenticatedLayout";
import DataTable from "@/back/Components/Table/DataTable";
import DataTableSearch from "@/back/Components/Table/DataTableSearch";
import DataTableFilterButton from "@/back/Components/Table/DataTableFilterButton";
import BtnOptionnalLinkWithTooltip from "@/back/Components/BtnOptionnalLinkWithTooltip";
import { GetFormatedDate, GetTranslation } from "@/lib/utils";
import { Views } from "@/back/lib/actions";
import { Resource } from "@/back/types/global";
import { ActivityLogModel } from "@/back/types/activity-logs";
import str from "@/hooks/use-string";
import useTrans from "@/hooks/use-translations";
import { Row } from "@tanstack/react-table";
import { usePage } from "@inertiajs/react";

type ActivityLogsEventEnumProps = Record<
  string,
  { value: number; name: string; label: string; tailwindclass: string }
>;

type ActivityLogsIndexProps = {
  activitylogModels: Resource;
  searchFields: string;
  activityLogsEventEnum: ActivityLogsEventEnumProps;
};

const getColumns = (activityLogsEventEnum: ActivityLogsEventEnumProps) => {
  return [
    {
      accessorKey: "event",
      header: ({ column }: { column: { id: string } }) => (
        <DataTableFilterButton
          label={str(
            GetTranslation(
              `laravel-activity-logs::trans.attributes.${column.id}`,
            ),
          )
            .ucFirst()
            .value()}
          field={column.id}
        />
      ),
      cell: ({ row }: { row: Row<ActivityLogModel> }) => {
        const eventValue = row.getValue<number>("event");

        const currentEvent = Object.values(activityLogsEventEnum).find(
          (event) => event.value === eventValue,
        );

        if (!currentEvent) {
          return GetTranslation(`laravel-activity-logs::trans.event_not_match`);
        }

        const bgClass = `bg-${currentEvent.tailwindclass}`;
        const hoverClass = `hover:bg-${currentEvent.tailwindclass}/90`;
        const textClass = `text-${currentEvent.tailwindclass}-foreground`;

        return (
          <Badge className={`${textClass} ${bgClass} ${hoverClass}`}>
            {str(GetTranslation(currentEvent.label)).ucFirst().value()}
          </Badge>
        );
      },
    },
    {
      accessorKey: "user_id",
      header: ({ column }: { column: { id: string } }) => (
        <DataTableFilterButton
          label={str(
            GetTranslation(
              `laravel-activity-logs::trans.attributes.${column.id}`,
            ),
          )
            .ucFirst()
            .value()}
          field={column.id}
        />
      ),
      cell: ({ row }: { row: Row<ActivityLogModel> }) => {
        const {
          user,
          user_id: userId,
          is_console: isConsole,
          is_anonymous: isAnonymous,
        } = row.original;

        if (user) {
          return (
            <BtnOptionnalLinkWithTooltip
              link={route("back.users.show", {
                user: userId,
              })}
              content={
                <>
                  <UserIcon />
                  {`${user.first_name} ${user.last_name}`}
                </>
              }
              tooltipContent={
                <p>
                  {GetTranslation(`laravel-activity-logs::trans.access_model`, {
                    params: {
                      model: GetTranslation(
                        "laravel-backend::models.classes.users",
                        { choice: 1 },
                      ),
                    },
                  })}
                </p>
              }
              size="sm"
              variant="secondary"
            />
          );
        }

        if (isConsole) {
          return GetTranslation(`laravel-activity-logs::trans.user_in_console`);
        }

        if (isAnonymous) {
          return GetTranslation(`laravel-activity-logs::trans.user_anonym`);
        }

        return GetTranslation(`laravel-activity-logs::trans.user_deleted`);
      },
    },
    {
      accessorKey: "model_class",
      header: ({ column }: { column: { id: string } }) => (
        <DataTableFilterButton
          label={str(
            GetTranslation(
              `laravel-activity-logs::trans.attributes.${column.id}`,
            ),
          )
            .ucFirst()
            .value()}
          field={column.id}
        />
      ),
      cell: ({ row }: { row: Row<ActivityLogModel> }) => {
        const modelClass = row.getValue<string>("model_class");
        const { event, model_id: modelId } = row.original;

        const modelSplit = modelClass.split("\\");
        const modelTargetName = modelSplit[modelSplit.length - 1];
        const model = str(modelTargetName).kebabCase().value();

        if (event !== activityLogsEventEnum.deleted.value) {
          return (
            <BtnOptionnalLinkWithTooltip
              link={route(`back.${str(model).plural().value()}.show`, modelId)}
              content={
                <>
                  <FileTextIcon />
                  {modelClass}
                </>
              }
              tooltipContent={
                <p>
                  {GetTranslation(`laravel-activity-logs::trans.access_model`, {
                    params: {
                      model: GetTranslation(
                        `laravel-activity-logs::trans.attributes.model_class`,
                      ),
                    },
                  })}
                </p>
              }
              size="sm"
              variant="secondary"
            />
          );
        }

        return <Badge variant="outline">{modelClass}</Badge>;
      },
    },
    {
      accessorKey: "created_at",
      header: ({ column }: { column: { id: string } }) => (
        <DataTableFilterButton
          label={str(
            GetTranslation(
              `laravel-activity-logs::trans.attributes.${column.id}`,
            ),
          )
            .ucFirst()
            .value()}
          field={column.id}
        />
      ),
      cell: ({ row }: { row: Row<ActivityLogModel> }) => {
        const createdAt = row.getValue<string>("created_at");

        return <Badge variant="secondary">{GetFormatedDate(createdAt)}</Badge>;
      },
    },
  ];
};

export default function Index({
  activitylogModels,
  searchFields,
  activityLogsEventEnum,
}: ActivityLogsIndexProps) {
  const actionModels = [Views.VISUALIZATION];
  const modelName = usePage().props.modelName as string;
  const columns = getColumns(activityLogsEventEnum);
  return (
    <AuthenticatedLayout
      title={str(
        useTrans(`laravel-activity-logs::models.classes.${modelName}`, {
          choice: 2,
        }),
      )
        .ucFirst()
        .value()}
    >
      <DataTableSearch modelRoute={modelName} searchFields={searchFields} />
      <DataTable
        columns={columns}
        modelRoute={modelName}
        modelsPaginate={activitylogModels}
        actions={actionModels}
      />
    </AuthenticatedLayout>
  );
}
