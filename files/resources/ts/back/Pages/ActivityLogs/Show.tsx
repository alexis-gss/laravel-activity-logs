// * Packages
import { ArrowLeftIcon, UserIcon } from "lucide-react";
import { usePage } from "@inertiajs/react";
import { route } from "ziggy-js";
// * Components shadcn
import { Badge } from "@/components/ui/badge";
// * Other
import AuthenticatedLayout from "@/back/Layouts/AuthenticatedLayout";
import DetailsTable from "@/back/Components/Table/DetailsTable";
import ModelActions from "@/back/Components/ModelActions";
import BtnOptionnalLinkWithTooltip from "@/back/Components/BtnOptionnalLinkWithTooltip";
import { AuthProp, SingleResource } from "@/back/types/global";
import { ActivityLogModel } from "@/back/types/activity-logs";
import str from "@/hooks/use-string";
import useTrans from "@/hooks/use-translations";
import { GetFormatedDate, GetTranslation } from "@/lib/utils";
import { AppEnv } from "@/types/global";

type ActivityLogsEventEnumProps = Record<
  string,
  { value: number; name: string; label: string; tailwindclass: string }
>;

type ActivityLogsShowProps = {
  data: SingleResource;
  activityLogsEventEnum: ActivityLogsEventEnumProps;
};

const getColumns = (
  routeName: string,
  activityLogModel: ActivityLogModel,
  activityLogsEventEnum: ActivityLogsEventEnumProps,
) => {
  return [
    {
      accessorKey: "event",
      header: str(
          GetTranslation(`laravel-activity-logs::trans.attributes.event`)
        )
        .ucFirst()
        .value(),
      cell: ({ value }: { value: number }) => {
        if (value) {
          const currentEvent = Object.values(activityLogsEventEnum).filter(
            (index) => index.value === value,
          );
          const bgClass = `bg-${currentEvent[0].tailwindclass}`;
          const hoverClass = `hover:bg-${currentEvent[0].tailwindclass}/90`;
          const textClass = `text-${currentEvent[0].tailwindclass}-foreground`;
          return currentEvent[0] ? (
            <Badge className={`${textClass} ${bgClass} ${hoverClass}`}>
              {str(GetTranslation(currentEvent[0].label)).ucFirst().value()}
            </Badge>
          ) : (
            GetTranslation(`laravel-activity-logs::trans.event_not_match`)
          );
        }
        return (
          <span className="text-sm italic text-muted-foreground">
            {GetTranslation("laravel-backend::back.table_not_known")}
          </span>
        );
      },
    },
    {
      accessorKey: "user_id",
      header: str(
        GetTranslation(`laravel-activity-logs::trans.attributes.user_id`),
      )
        .ucFirst()
        .value(),
      cell: ({ value }: { value: number }) => {
        if (value && activityLogModel.user)
          return (
            <BtnOptionnalLinkWithTooltip
              link={route(`${routeName}users.show`, { user: value })}
              content={
                <>
                  <UserIcon />
                  {`${activityLogModel.user.first_name} ${activityLogModel.user.last_name}`}
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
        if (activityLogModel.is_console)
          return GetTranslation(`laravel-activity-logs::trans.user_in_console`);
        if (activityLogModel.is_anonymous)
          return GetTranslation(`laravel-activity-logs::trans.user_anonym`);
        return GetTranslation(`laravel-activity-logs::trans.user_deleted`);
      },
    },
    {
      accessorKey: "model_class",
      header: str(
        GetTranslation(`laravel-activity-logs::trans.attributes.model_class`),
      )
        .ucFirst()
        .value(),
      cell: ({ value }: { value: string }) => {
        const modelSplit = value.split("\\");
        const modelTargetName = modelSplit[modelSplit.length - 1];
        const model = str(modelTargetName).kebabCase().plural().value();
        return activityLogModel.event !==
          activityLogsEventEnum.deleted.value ? (
          <BtnOptionnalLinkWithTooltip
            link={route(`${routeName}${model}.show`, activityLogModel.model_id)}
            content={
              <>
                <UserIcon />
                {value}
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
        ) : (
          <code>{value}</code>
        );
      },
    },
    {
      accessorKey: "modifications",
      header: str(
        GetTranslation(`laravel-activity-logs::trans.attributes.modifications`),
      )
        .ucFirst()
        .value(),
      cell: ({ value }: { value: object }) => {
        return value && Object.entries(value).length ? (
          <ul className="list-disc ps-4">
            {Object.entries(value).map(([val, text]) => {
              return (
                <li key={val}>
                  {val} <span className="text-muted-foreground">({text})</span>
                </li>
              );
            })}
          </ul>
        ) : (
          <span className="text-sm italic text-muted-foreground">
            {GetTranslation(`laravel-activity-logs::trans.no_changes`)}
          </span>
        );
      },
    },
    {
      accessorKey: "created_at",
      header: str(
        GetTranslation(`laravel-activity-logs::trans.attributes.created_at`),
      )
        .ucFirst()
        .value(),
      cell: ({ value }: { value: string }) => (
        <Badge variant="secondary">{GetFormatedDate(value)}</Badge>
      ),
    },
  ];
};

export default function Show({
  data,
  activityLogsEventEnum,
}: ActivityLogsShowProps) {
  const { auth, appEnv } = usePage().props as unknown as {
    auth: AuthProp;
    appEnv: AppEnv;
  };
  const { viewAny } = auth.policies.activityLogs;
  const modelName = usePage().props.modelName as string;
  const activityLogModel = data.data as ActivityLogModel;
  const columns = getColumns(
    appEnv.routes.name,
    activityLogModel,
    activityLogsEventEnum,
  );
  const transBackToList = useTrans("laravel-backend::back.crud_back_to_list");
  const beforeNode = () => {
    return (
      viewAny && (
        <div className="flex justify-end items-center gap-x-1.5">
          <BtnOptionnalLinkWithTooltip
            link={route(`${appEnv.routes.name}${modelName}.index`)}
            content={<ArrowLeftIcon />}
            tooltipContent={<p>{transBackToList}</p>}
          />
        </div>
      )
    );
  };

  return (
    <AuthenticatedLayout
      title={useTrans("laravel-backend::crud.helpers.visualization_model", {
        params: {
          model: useTrans(
            `laravel-activity-logs::models.classes.${modelName}`,
            {
              choice: 1,
            },
          ),
        },
      })}
      beforeNode={beforeNode()}
    >
      <ModelActions
        modelRoute={modelName}
        model={activityLogModel}
        modelUpdatedAt={activityLogModel.created_at.toString()}
        message={useTrans(`laravel-activity-logs::trans.get_data_message`)}
      />
      <DetailsTable
        columns={columns}
        model={activityLogModel}
        forceRenderValue
      />
    </AuthenticatedLayout>
  );
}
